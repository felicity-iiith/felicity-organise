<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?= $name ?> - Felicity'16 Organise</title>
        <link rel="stylesheet" href="<?= base_url() ?>css/thoda.min.css">
        <link rel="stylesheet" href="<?= base_url() ?>css/common.css">
        <link rel="stylesheet" href="<?= base_url() ?>css/directory.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    </head>
    <body>
        <div class="container">
            <h1 class="file_title">Editing file: <?= $name ?></h1>
            <nav>
                <a class="btn btn-blue" href=".."><i class="fa fa-arrow-left"></i> Go back to directory</a>
            </nav>
            <div class="error"><?= $error ?></div>
            <div class="row">
                <div class="col-6-12 padded" style="border-right: 1px solid #ccc;">
                    <form class="block" action="" method="post">
                        <h2>Add file/directory</h2>
                        <input type="hidden" name="parent_id" value="<?= $id ?>"/>
                        <label for="filename">Name: <input type="text" name="name" /></label>
                        <label for="slug">Slug: <input type="text" name="slug" /></label>
                        <label for="type">Type:
                            <select name="type">
                                <option value="file">file</option>
                                <option value="directory">directory</option>
                            </select>
                        </label>

                        <input type="submit" class="btn-green" name="add" value="Add"/>
                    </form>
                </div>
                <div class="col-6-12 padded">
                    <form class="block" action="" method="post">
                        <h2>Rename directory</h2>
                        <input type="hidden" name="file_id" value="<?= $id ?>"/>
                        <label for="filename">Name: <input type="text" name="name" value="<?= $name ?>" /></label>
                        <label for="slug">Slug: <input type="text" name="slug" value="<?= $slug ?>" /></label>
                        <input type="submit" class="btn-green" name="save" value="Save"/>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
