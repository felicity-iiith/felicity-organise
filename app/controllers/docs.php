<?php

class docs extends Controller {

    function __construct() {
        $this->load_library("http_lib", "http");
        $this->load_library("cas_lib", "cas");
        $this->cas->forceAuthentication();

        $this->load_model("docs_model");
        $this->load_model("auth_model");

        $this->user = $this->cas->getUser();
        $this->is_admin = $this->auth_model->is_admin($this->user);
    }

    private function edit() {
        if (!empty($_POST["save"]) && !empty($_POST["file_id"])
            && !empty($_POST["name"]) && !empty($_POST["slang"]))
        {
            $file_id = $_POST["file_id"];
            $name = $_POST["name"];
            $slang = $_POST["slang"];
            $data = $_POST["data"] ?: "";
            $save = $this->docs_model->update_file($file_id, $name, $slang, $data);
            if ($save === false) {
                return "Could not save file";
            }

            $path = $this->docs_model->get_file_path($file_id);
            $this->http->redirect(base_url() . "docs" . $path . "edit/");
        }

        if (!empty($_POST["add"]) && !empty($_POST["parent_id"])
            && !empty($_POST["name"]) && !empty($_POST["slang"]))
        {
            $parent_id = $_POST["parent_id"];
            $name = $_POST["name"];
            $slang = $_POST["slang"];
            $type = $_POST["type"];

            $add = $this->docs_model->new_file($parent_id, $name, $slang, $type, $this->user);
            if ($add === false) {
                return "Could not add file";
            }

            $path = $this->docs_model->get_file_path($parent_id) . $slang . "/";
            $this->http->redirect(base_url() . "docs" . $path . "edit/");
        }
    }

    function read() {
        $path = func_get_args();

        $edit = false;
        if ($this->is_admin && count($path)
            && in_array($path[count($path) - 1], ["edit", "new"]))
        {
            $action = array_pop($path);
            $edit = true;
        }

        $file_id = $this->docs_model->get_path_id($path);
        $file_type = $this->docs_model->get_file_type($file_id);
        $file = $this->docs_model->get_file($file_id);

        if ($this->is_admin && $edit) {
            $error = $this->edit();

            if ($file_type == "directory") {
                $file["error"] = $error;
                $this->load_view("directory_edit", $file);
            } else if ($file_type == "file") {
                $file["error"] = $error;
                $this->load_view("file_edit", $file);
            } else {
                $this->http->err_404();
            }
        } else {
            if ($file_type == "directory") {
                $file["data"] = $this->docs_model->get_directory($file_id);
                $file["is_admin"] = $this->is_admin;
                $this->load_view("directory", $file);
            } else if ($file_type == "file") {
                $file["is_admin"] = $this->is_admin;
                $this->load_view("file", $file);
            } else {
                $this->http->err_404();
            }
        }
    }

}
