<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?= $name ?> - Felicity'16 Organise</title>
        <script src="<?= base_url() ?>js/lib/marked.min.js"></script>
        <link rel="stylesheet" href="<?= base_url() ?>css/thoda.min.css">
        <link rel="stylesheet" href="<?= base_url() ?>css/common.css">
        <link rel="stylesheet" href="<?= base_url() ?>css/file.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <script>
            mdText = '';
            function setHeight(fieldId){
                var file_md_edit = document.getElementById('file_md_edit');
                var dummy = document.getElementById('dummyTextarea');
                dummy.value = file_md_edit.value;

                file_md_edit.style.height = (dummy.scrollHeight + 20) + 'px';
            }
            function updateMD () {
                setHeight();
                // Convert markdown
                var file_md = document.getElementById('file_md');
                var file_md_edit = document.getElementById('file_md_edit');
                if (mdText == file_md_edit.value) return;
                mdText = file_md_edit.value;
                file_md.innerHTML = marked(mdText);
            }
            function setupEdit() {
                var file_md_edit = document.getElementById('file_md_edit');
                file_md_edit.addEventListener('keypress', setHeight);
                file_md_edit.addEventListener('keyup', setHeight);

                updateMD();
                window.setInterval(updateMD, 1000);
            }
        </script>
    </head>
    <body onload="setupEdit()">
        <nav>
            <a class="btn btn-blue" href=".."><i class="fa fa-arrow-left"></i> Go back to file (Discard changes)</a>
        </nav>
        <div class="error"><?= $error ?></div>
        <article class="file">
            <form action="" method="post">
                <input type="hidden" name="file_id" value="<?= $id ?>"/>
                <div class="file_title_edit">
                    <label for="filename">Name: </label><input type="text" name="name" value="<?= $name ?>" required />
                    <label for="slug">Slug: </label><input type="text" name="slug" value="<?= $slug ?>" required />
                    <input type="submit" class="btn btn-green" name="save" value="Save page"/>
                </div>
                <div class="editor">
                    <div id="file_edit_contain">
                        <textarea id="file_md_edit" class="file_content" name="data" placeholder="Write you markdown text here." autofocus><?= $data ?></textarea>
                        <textarea id="dummyTextarea"></textarea>
                    </div>
                    <section id="file_md" class="file_content">
                        <?= $data ?>
                    </section>
                </div>
            </form>
        </article>
    </body>
</html>
