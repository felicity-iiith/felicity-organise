<div class="padded" id="useredit">
    <form action="" method="post">
        <h2>Users and permissions</h2>
        <input type="hidden" name="file_id" value="<?= $id ?>"/>
        <input type="text" name="username" placeholder="Username to be added" required />
        <input type="submit" class="btn-green inline" name="add_user" value="Add user"/>
    </form>
    <ul class="admin_list">
        <?php
            foreach ($admins as $admin):
                $inherited = ($admin["file_id"] != $id);
        ?>
                <li>
                    <?= $admin["user"] ?>
                    <?php
                        if ($inherited):
                    ?>
                        <span class="grey">(Inherited)</span>
                    <?php
                        elseif ($user == $admin["user"]):
                    ?>
                        <span class="grey">(It's you)</span>
                    <?php
                        else:
                    ?>
                        <form action="" method="post" style="display: inline-block;">
                            <input type="hidden" name="file_id" value="<?= $id ?>"/>
                            <input type="hidden" name="username" value="<?= $admin["user"] ?>"/>
                            <button type="submit" name="revoke_user" value="Revoke permissions" class="btn btn-small btn-red">
                                <i class="fa fa-trash-o"></i>
                                Revoke permissions
                            </button>
                        </form>
                    <?php
                        endif;
                    ?>
                </li>
        <?php
            endforeach;
        ?>
    </ul>
</div>
