<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?= $name ?> - Felicity'16 Organise</title>
        <script src="<?= base_url() ?>js/common.js"></script>
        <link rel="stylesheet" href="<?= base_url() ?>css/common.css">
        <link rel="stylesheet" href="<?= base_url() ?>css/directory.css">
    </head>
    <body>
        <nav>
            <a href="..">Go back to directory</a>
        </nav>
        <div class="error"><?= $error ?></div>
        <form action="" method="post">
            <h1>Rename Directory</h1>
            <input type="hidden" name="file_id" value="<?= $id ?>"/>
            <label for="filename">Name: </label><input type="text" name="name" value="<?= $name ?>" /><br>
            <label for="slug">Slug: </label><input type="text" name="slug" value="<?= $slug ?>" /><br>
            <input type="submit" name="save" value="Save"/>
        </form>
        <hr>
        <form action="" method="post">
            <h1>Add file</h1>
            <input type="hidden" name="parent_id" value="<?= $id ?>"/>
            <label for="name">Name: </label><input type="text" name="name" id="newname"/><br>
            <label for="slug">Slug: </label><input type="text" name="slug" id="newslug" /><br>
            <label for="type">Type: </label>
            <select name="type">
                <option value="directory">directory</option>
                <option value="file">File</option>
            </select><br>
            <input type="submit" name="add" value="Add"/>
        </form>
        <script>
            (function() {
                var newname = document.getElementById("newname");

                function updateSlug() {
                    var newslug = document.getElementById("newslug");
                    newslug.value = getSlug(newname.value);
                }

                newname.addEventListener('keyup', updateSlug);
                newname.addEventListener('blur', updateSlug);
            })();
        </script>
    </body>
</html>
