<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?= $name ?> - Felicity'16 Organise</title>
        <script src="<?= base_url() ?>js/lib/marked.min.js"></script>
        <script src="<?= base_url() ?>js/common.js"></script>
        <script src="<?= base_url() ?>js/common_edit.js"></script>
        <link rel="stylesheet" href="<?= base_url() ?>css/thoda.min.css">
        <link rel="stylesheet" href="<?= base_url() ?>css/common.css">
        <link rel="stylesheet" href="<?= base_url() ?>css/common_edit.css">
        <link rel="stylesheet" href="<?= base_url() ?>css/file.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <script>
            var mdText = '',
                origFile,
                file_md_edit,
                file_md, file_name_edit,
                file_slug_edit,
                dummyText,
                ignore_change;
            function setHeight(fieldId){
                dummyText.value = file_md_edit.value;

                file_md_edit.style.height = (dummyText.scrollHeight + 20) + 'px';
            }
            function updateMD () {
                // Update ignore changes button
                if (origFile.name != escapeHtml(file_name_edit.value) ||
                    origFile.slug != escapeHtml(file_slug_edit.value) ||
                    origFile.data != escapeHtml(file_md_edit.value)
                ) {
                    ignore_change.innerHTML = "(Discard changes)";
                } else {
                    ignore_change.innerHTML = "";
                }
                // Set height of textarea
                setHeight();
                // Convert markdown
                if (mdText == file_md_edit.value) return;
                mdText = file_md_edit.value;
                file_md.innerHTML = marked(mdText);
            }
            function setupEdit() {
                file_md_edit = document.getElementById('file_md_edit');
                file_md = document.getElementById('file_md');
                file_name_edit = document.getElementById('editname');
                file_slug_edit = document.getElementById('editslug');
                dummyText = document.getElementById('dummyTextarea');
                ignore_change = document.getElementById('ignore_change');
                origFile = {
                    name: document.getElementById('orig_file_name').innerHTML,
                    slug: document.getElementById('orig_file_slug').innerHTML,
                    data: document.getElementById('orig_file_data').innerHTML,
                };
                file_md_edit.addEventListener('keypress', setHeight);
                file_md_edit.addEventListener('keyup', setHeight);

                updateMD();
                window.setInterval(updateMD, 1000);

                if (!window.location.hash) {
                    file_md_edit.focus();
                }
            }
        </script>
    </head>
    <body onload="setupEdit()">
        <nav>
            <a class="btn btn-blue" href="."><i class="fa fa-arrow-left"></i> Go back to file <span id="ignore_change"></span></a>
            <?php if ($user_can['manage_user']): ?>
            <a class="btn btn-blue" href="#useredit"><i class="fa fa-user"></i> Edit user permissions (scroll down) <span id="ignore_change"></span></a>
            <?php endif; ?>
        </nav>
        <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <article class="file">
            <form action="" method="post" class="file_edit">
                <input type="hidden" name="file_id" value="<?= $id ?>"/>
                <div class="file_title_edit">
                    <label for="filename">Name: </label><input type="text" name="name" id="editname" value="<?= isset($unsaved) ? $unsaved["name"] : $name ?>" required />
                    <label for="slug">Slug: </label><input type="text" name="slug" id="editslug" value="<?= isset($unsaved) ? $unsaved["slug"] : $slug ?>" required />
                    <input type="submit" class="btn btn-green" name="save" value="Save page"/>
                </div>
                <div id="orig_file">
                    <div id="orig_file_name" hidden><?= $name ?></div>
                    <div id="orig_file_slug" hidden><?= $slug ?></div>
                    <div id="orig_file_data" hidden><?= $data ?></div>
                </div>
                <div class="editor">
                    <div id="file_edit_contain">
                        <textarea id="file_md_edit" class="file_content" name="data"
                            placeholder="Write your markdown text here."
                            ><?= isset($unsaved) ? $unsaved["data"] : $data ?></textarea>
                        <textarea id="dummyTextarea"></textarea>
                    </div>
                    <section id="file_md" class="file_content"></section>
                </div>
            </form>
            <?php
                $this->load_fragment('user_edit');
            ?>
            <?php
                $this->load_fragment('delete_file');
            ?>
        </article>
    </body>
</html>
