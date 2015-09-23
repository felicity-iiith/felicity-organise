<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?= $name ?> - Felicity'16 Organise</title>
        <link rel="stylesheet" href="<?= base_url() ?>css/directory.css">
    </head>
    <body>
        <div class="error"><?= $error ?></div>
        <form action="" method="post">
            <h1>Rename Directory</h1>
            <input type="hidden" name="file_id" value="<?= $id ?>"/>
            <label for="filename">Name: </label><input type="text" name="name" value="<?= $name ?>" /><br>
            <label for="slang">Slang: </label><input type="text" name="slang" value="<?= $slang ?>" /><br>
            <input type="submit" name="save" value="Save"/>
        </form>
        <hr>
        <form action="" method="post">
            <h1>Add file</h1>
            <input type="hidden" name="parent_id" value="<?= $id ?>"/>
            <label for="filename">Name: </label><input type="text" name="name" /><br>
            <label for="slang">Slang: </label><input type="text" name="slang" /><br>
            <label for="type">Type: </label>
            <select name="type">
                <option value="directory">directory</option>
                <option value="file">File</option>
            </select><br>
            <input type="submit" name="add" value="Add"/>
        </form>
    </body>
</html>
