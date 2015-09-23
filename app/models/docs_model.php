<?php

class docs_model extends Model {

    function new_directory($parent, $name, $slang, $user) {
        $stmt = $this->DB->prepare("INSERT INTO `files` (`name`, `slang`, `parent`, `type`, `created_by`) VALUES (?, ?, ?, 'directory', ?)");
        if (!$stmt->bind_param("ssis", $name, $slang, $parent, $user)) {
            return false;
        }
        if (!$stmt->execute()) {
            return false;
        }
        return true;
    }

    function new_file($parent, $name, $slang, $data, $user) {
        $stmt = $this->DB->prepare("INSERT INTO `files` (`name`, `slang`, `parent`, `type`, `data`, `created_by`) VALUES (?, ?, ?, 'file',? ,?)");
        if (!$stmt->bind_param("ssiss", $name, $slang, $parent, $data, $user)) {
            return false;
        }
        if (!$stmt->execute()) {
            return false;
        }
        return true;
    }

    function update_file($file_id, $name, $slang, $data) {
        if ($file_id === false) {
            return false;
        }
        $stmt = $this->DB->prepare("UPDATE `files` SET `name`=?, `slang`=?, `data`=? WHERE `id`=?");
        if (!$stmt->bind_param("sssi", $name, $slang, $data, $file_id)) {
            return false;
        }
        if (!$stmt->execute()) {
            return false;
        }
        return true;
    }

    function get_slang_id($parent, $slang) {
        $stmt = $this->DB->prepare("SELECT `id` FROM `files` WHERE `parent`=? AND `slang`=?");
        if (!$stmt->bind_param("is", $parent, $slang)) {
            return false;
        }
        if (!$stmt->execute()) {
            return false;
        }
        if ($row = $stmt->get_result()->fetch_row()) {
            return $row[0];
        }
        return false;
    }

    function get_path_id($path) {
        $path = array_filter($path);
        $parent = 0;
        foreach ($path as $component) {
            $parent = $this->get_slang_id($parent, $component);
            if ($parent === false) {
                return false;
            }
        }
        return $parent;
    }

    function get_file_path($file_id) {
        if ($file_id === false) {
            return false;
        }
        $path = '/';
        do {
            $file = $this->get_file($file_id);
            $file_id = $file['parent'];
            $path = '/' .$file['slang'] . $path;
        } while($file_id !== 0);
        return $path;
    }

    function get_file_type($file_id) {
        if ($file_id === false) {
            return false;
        }
        $stmt = $this->DB->prepare("SELECT `type` FROM `files` WHERE `id`=?");
        if (!$stmt->bind_param("i", $file_id)) {
            return false;
        }
        if (!$stmt->execute()) {
            return false;
        }
        if ($row = $stmt->get_result()->fetch_row()) {
            return $row[0];
        }
        return false;
    }

    function get_directory($file_id) {
        // Get list of files in directory
        if ($file_id === false) {
            return false;
        }
        $stmt = $this->DB->prepare("SELECT `id`, `name`, `slang`, `parent`, `type` FROM `files` WHERE `parent`=?");
        if (!$stmt->bind_param("i", $file_id)) {
            return false;
        }
        if (!$stmt->execute()) {
            return false;
        }
        $page_list = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $page_list;
    }

    function get_file($file_id) {
        // Get details of file
        if ($file_id === false) {
            return false;
        }
        $stmt = $this->DB->prepare("SELECT `id`, `name`, `slang`, `parent`, `data` FROM `files` WHERE `id`=?");
        if (!$stmt->bind_param("i", $file_id)) {
            return false;
        }
        if (!$stmt->execute()) {
            return false;
        }
        if ($row = $stmt->get_result()->fetch_assoc()) {
            return $row;
        }
        return false;
    }
    //
    // function list_pages($cat_id) {
    //     $stmt = $this->DB->prepare("SELECT `id`, `path`, `template` FROM `pages` WHERE `cat_id`=?");
    //     if (!$stmt->bind_param("i", $cat_id)) {
    //         return false;
    //     }
    //     if (!$stmt->execute()) {
    //         return false;
    //     }
    //     $page_list = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    //     return $page_list;
    // }
}
