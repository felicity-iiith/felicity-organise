<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?= $name ?> - Felicity'16 Organise</title>
        <link rel="stylesheet" href="<?= base_url() ?>css/directory.css">
    </head>
    <body>
        <article class="dir">
            <h1 class="dir_title"><?= $name ?></h1>
            <ul class="file_list">
                <?php
                    foreach ($data as $file) {
                        echo '<li><a href="' . $file['slang'] . '">' . $file['name'] . '</a></li>';
                    }
                ?>

            </ul>
        </article>
    </body>
</html>
