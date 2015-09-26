<?php

class docs_model extends Model {

    function new_file($parent, $name, $slug, $type, $user) {
        $stmt = $this->DB->prepare("INSERT INTO `files` (`name`, `slug`, `parent`, `type`, `created_by`) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt->bind_param("ssiss", $name, $slug, $parent, $type, $user)) {
            return false;
        }
        if (!$stmt->execute()) {
            return false;
        }
        return true;
    }

    function update_file($file_id, $name, $slug, $data, $user) {
        if ($file_id === false) {
            return false;
        }
        $file_type = $this->get_file_type($file_id);
        if ($file_type === false) {
            return false;
        }
        $db_error = false;
        $this->DB->autocommit(false);

        $stmt = $this->DB->prepare("UPDATE `files` SET `name`=?, `slug`=? WHERE `id`=?");
        if (!$stmt->bind_param("ssi", $name, $slug, $file_id)) {
            $db_error = true;
        }
        if (!$stmt->execute()) {
            $db_error = true;
        }

        if ($file_type != 'directory') {
            $stmt = $this->DB->prepare("INSERT INTO `file_data` (`file_id`, `data`, `created_by`) VALUES (?, ?, ?)");
            if (!$stmt->bind_param("iss", $file_id, $data, $user)) {
                $db_error = true;
            }
            if (!$stmt->execute()) {
                $db_error = true;
            }
        }

        if ($db_error) {
            $this->DB->rollback();
        } else {
            $this->DB->commit();
        }

        $this->DB->autocommit(true);
        return !$db_error;
    }

    function get_slug_id($parent, $slug) {
        $stmt = $this->DB->prepare("SELECT `id` FROM `files` WHERE `parent`=? AND `slug`=?");
        if (!$stmt->bind_param("is", $parent, $slug)) {
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
            $parent = $this->get_slug_id($parent, $component);
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
        if ($file_id == 0) {
            return '/';
        }
        $path = '/';
        do {
            $file = $this->get_file($file_id);
            $file_id = $file['parent'];
            $path = '/' .$file['slug'] . $path;
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
        $stmt = $this->DB->prepare("SELECT `id`, `name`, `slug`, `parent`, `type` FROM `files` WHERE `parent`=?");
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
        $stmt = $this->DB->prepare("SELECT `id`, `name`, `slug`, `parent` FROM `files` WHERE `id`=?");
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

    function get_file_data($file_id) {
        // Get data for file
        if ($file_id === false) {
            return false;
        }
        $stmt = $this->DB->prepare("SELECT `data` FROM `file_data` WHERE `file_id`=? ORDER BY `id` DESC LIMIT 1");
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

    function get_history($file_id) {
        // Get list of edits for file
        if ($file_id === false) {
            return false;
        }
        $stmt = $this->DB->prepare("SELECT `id`, `timestamp`, `created_by` FROM `file_data` WHERE `file_id`=? ORDER BY `id` DESC");
        if (!$stmt->bind_param("i", $file_id)) {
            return false;
        }
        if (!$stmt->execute()) {
            return false;
        }
        $history_list = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $history_list;
    }

    function get_history_item($file_id, $edit_id) {
        // Get list of edits for file
        if ($file_id === false || $edit_id === false) {
            return false;
        }
        $stmt = $this->DB->prepare("SELECT `id`, `data`, `timestamp`, `created_by` FROM `file_data` WHERE `file_id`=? AND `id`<=? ORDER BY `id` DESC LIMIT 2");
        if (!$stmt->bind_param("ii", $file_id, $edit_id)) {
            return false;
        }
        if (!$stmt->execute()) {
            return false;
        }
        $history_list = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $history_list;
    }

}
