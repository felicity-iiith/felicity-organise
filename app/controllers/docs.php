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

    private function is_slug_valid($slug) {
        return preg_match('/^[a-z0-9-_]+$/i', $slug);
    }

    private function edit() {
        if (!empty($_POST["save"]) && isset($_POST["file_id"])
            && !empty($_POST["name"])
            && (!empty($_POST["slug"]) || $_POST["file_id"] == 0)
        ) {
            $file_id = $_POST["file_id"];
            $name = $_POST["name"];
            $slug = @$_POST["slug"] ?: "";
            $data = @$_POST["data"] ?: "";

            if ($slug && !$this->is_slug_valid($slug)) {
                return "Invalid slug";
            }

            $save = $this->docs_model->update_file($file_id, $name, $slug, $data, $this->user);
            if ($save === false) {
                return "Could not save file";
            }

            $path = $this->docs_model->get_file_path($file_id);
            $this->http->redirect(base_url() . "docs" . $path . "edit/");
        }

        if (!empty($_POST["add"]) && isset($_POST["parent_id"])
            && !empty($_POST["name"]) && !empty($_POST["slug"])
        ) {
            $parent_id = $_POST["parent_id"];
            $name = $_POST["name"];
            $slug = $_POST["slug"];
            $type = $_POST["type"];

            if (!$this->is_slug_valid($slug)) {
                return "Invalid slug";
            }

            $add = $this->docs_model->new_file($parent_id, $name, $slug, $type);
            if ($add === false) {
                return "Could not add file";
            }

            $path = $this->docs_model->get_file_path($parent_id) . $slug . "/";
            $this->http->redirect(base_url() . "docs" . $path . "edit/");
        }

        if (!empty($_POST["add_user"]) && isset($_POST["file_id"])
            && !empty($_POST["username"])
        ) {
            $file_id = $_POST["file_id"];
            $username = $_POST["username"];

            $add = $this->docs_model->add_admin($file_id, $username);
            if ($add === false) {
                return "Could not add user";
            }

            $path = $this->docs_model->get_file_path($file_id);
            $this->http->redirect(base_url() . "docs" . $path . "edit/#useredit");
        }

        if (!empty($_POST["revoke_user"]) && isset($_POST["file_id"])
            && !empty($_POST["username"])
        ) {
            $file_id = $_POST["file_id"];
            $username = $_POST["username"];

            $add = $this->docs_model->remove_admin($file_id, $username);
            if ($add === false) {
                return "Could not revoke permissions for user";
            }

            $path = $this->docs_model->get_file_path($file_id);
            $this->http->redirect(base_url() . "docs" . $path . "edit/#useredit");
        }

        if (!empty($_POST["delete_file"]) && isset($_POST["file_id"])) {
            $file_id = $_POST["file_id"];
            $file = $this->docs_model->get_file($file_id);
            $parent_id = @$file['parent'] ?: 0;
            $file_type = @$file['type'] ?: false;

            if ($file_type == 'directory') {
                $file_list = $this->docs_model->get_directory($file_id);
                if (count($file_list)) {
                    return "Cannot delete non-empty directory";
                }
            }

            $delete = $this->docs_model->delete_file($file_id, $this->user);
            if ($delete === false) {
                return "Could not delete " . $file_type;
            }

            $path = $this->docs_model->get_file_path($parent_id);
            $this->http->redirect(base_url() . "docs" . $path);
        }
    }

    function read() {
        $path = func_get_args();

        $action = false;
        if (count($path)
            && in_array($path[count($path) - 1], ["edit", "history"])
        ) {
            $action = array_pop($path);
        }

        $file_id = $this->docs_model->get_path_id($path);
        $file = $this->docs_model->get_file($file_id);
        $file_type = $file ? $file['type'] : false;

        $this->is_admin = $this->is_admin ||
            $this->docs_model->has_permission($file_id, $this->user);

        if ($action == 'edit') {
            if (!$this->is_admin) {
                $this->http->err_404();
            }

            $error = $this->edit();

            if ($file_type == "directory") {
                $file["error"] = $error;
                $file["admins"] = $this->docs_model->get_admin_list($file_id);
                $file["user"] = $this->user;
                $this->load_view("directory_edit", $file);
            } else if ($file_type == "file") {
                $file["error"] = $error;
                $file["admins"] = $this->docs_model->get_admin_list($file_id);
                $file["user"] = $this->user;
                $file["data"] = $this->docs_model->get_file_data($file_id);
                $this->load_view("file_edit", $file);
            } else {
                $this->http->err_404();
            }
        } else if ($action == 'history'){
            if ($file_type == "file") {
                $file["history"] = $this->docs_model->get_history($file_id);

                $file["is_admin"] = $this->is_admin;
                if ($this->is_admin && isset($_GET["id"])) {
                    $edit_id = $_GET["id"];
                    $file["history_item"] = $this->docs_model->get_history_item($file_id, $edit_id);
                } else if (isset($_GET["id"])) {
                    $file["perm_error"] = true;
                }

                $this->load_view("file_history", $file);
            } else {
                $this->http->err_404();
            }
        } else {
            if ($file_type == "directory") {
                $file["data"] = $this->docs_model->get_directory($file_id);
                $file["is_admin"] = $this->is_admin;
                $this->load_view("directory", $file);
            } else if ($file_type == "file") {
                $file["data"] = $this->docs_model->get_file_data($file_id);
                $file["is_admin"] = $this->is_admin;
                $this->load_view("file", $file);
            } else {
                $this->http->err_404();
            }
        }
    }

}
