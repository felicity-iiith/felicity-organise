<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?= $name ?> - Felicity'16 Organise</title>
        <link rel="stylesheet" href="<?= base_url() ?>css/common.css">
        <link rel="stylesheet" href="<?= base_url() ?>css/directory.css">
    </head>
    <body>
        <?php
            if ($is_admin):
        ?>
        <div class="admin_panel">
            <a href="edit">Edit</a>
        </div>
        <?php
            endif;
        ?>
        <article class="dir">
            <h1 class="dir_title"><?= $name ?></h1>
            <ul class="file_list">
                <?php
                    if ($parent != -1) {
                        echo '<li><a href="..">(Go to parent)</a></li>';
                    }
                    foreach ($data as $file) {
                        echo '<li><a href="' . $file['slug'] . '">' . $file['name'] . '</a></li>';
                    }
                ?>

            </ul>
        </article>
    </body>
</html>
