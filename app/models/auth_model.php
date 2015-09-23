<?php

class auth_model extends Model {

    function is_admin($user) {
        global $admins;

        if (in_array($user, $admins)) {
            return true;
        }
        return false;
    }
}
