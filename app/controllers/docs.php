<?php

class docs extends Controller {

    function __construct() {
        $this->load_library("http_lib", "http");
        $this->load_library("cas_lib", "cas");
        $this->cas->forceAuthentication();

        $this->load_model("docs_model");
        $this->load_model("perms_model");
        $this->load_model("auth_model");

        $this->user = $this->cas->getUser();
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
            $name = htmlspecialchars($_POST["name"]);
            $slug = htmlspecialchars(@$_POST["slug"] ?: "");
            $data = htmlspecialchars(@$_POST["data"] ?: "");

            if ($slug && !$this->is_slug_valid($slug)) {
                return "Invalid slug";
            }

            $save = $this->docs_model->update_file($file_id, $name, $slug, $data, $this->user);
            if ($save === false) {
                return "Could not save file";
            }

            $path = $this->docs_model->get_file_path($file_id);
            $this->http->redirect(base_url() . "docs" . $path . "?edit");
        }

        if (!empty($_POST["add"]) && isset($_POST["parent_id"])
            && !empty($_POST["name"]) && !empty($_POST["slug"])
        ) {
            $parent_id = $_POST["parent_id"];
            $name = $_POST["name"];
            $slug = $_POST["slug"];
            $type = $_POST["type"];
            $default_role = $_POST["default_role"];

            if (!$this->is_slug_valid($slug)) {
                return "Invalid slug";
            }

            $add = $this->docs_model->new_file($parent_id, $name, $slug, $type, $default_role, $this->user);
            if ($add === false) {
                return "Could not add file";
            }

            $path = $this->docs_model->get_file_path($parent_id) . $slug . "/";
            $this->http->redirect(base_url() . "docs" . $path . "?edit");
        }

        if (!empty($_POST["update_default_role"]) && isset($_POST["file_id"])) {
            if (!$this->user_can['manage_user']) {
                $this->http->response_code(403);
            }

            $file_id = $_POST["file_id"];
            $default_role = $_POST["default_role"];

            if (false === $this->perms_model->set_default_role($file_id, $default_role)) {
                return "Could not update default role";
            }

            $path = $this->docs_model->get_file_path($file_id);
            $this->http->redirect(base_url() . "docs" . $path . "?edit#useredit");
        }

        if (!empty($_POST["add_user"]) && isset($_POST["file_id"])
            && !empty($_POST["username"])
        ) {
            if (!$this->user_can['manage_user']) {
                $this->http->response_code(403);
            }

            $file_id = $_POST["file_id"];
            $username = $_POST["username"];
            $role = $_POST["role"];

            $add = $this->perms_model->add_user_role($file_id, $username, $role);
            if ($add === false) {
                return "Could not add user";
            }

            $path = $this->docs_model->get_file_path($file_id);
            $this->http->redirect(base_url() . "docs" . $path . "?edit#useredit");
        }

        if (!empty($_POST["revoke_user"]) && isset($_POST["file_id"])
            && !empty($_POST["username"])
        ) {
            if (!$this->user_can['manage_user']) {
                $this->http->response_code(403);
            }

            $file_id = $_POST["file_id"];
            $username = $_POST["username"];

            $add = $this->perms_model->remove_user_role($file_id, $username);
            if ($add === false) {
                return "Could not revoke permissions for user";
            }

            $path = $this->docs_model->get_file_path($file_id);
            $this->http->redirect(base_url() . "docs" . $path . "?edit#useredit");
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
        if (isset($_GET["edit"])) {
            $action = "edit";
        } else if (isset($_GET["history"])) {
            $action = "history";
        }

        $file_id = $this->docs_model->get_path_id($path);
        $file = $this->docs_model->get_file($file_id);
        $file_type = $file ? $file['type'] : false;

        $this->user_can = $this->perms_model->get_permissions($file_id, $this->user);

        if ($action == 'edit') {
            if (!$this->user_can['write_file']) {
                $this->http->response_code(403);
            }

            $error = $this->edit();

            if ($file_type == "directory") {
                $file["error"] = $error;
                $file["admins"] = $this->perms_model->get_user_list($file_id);
                $file["user"] = $this->user;
                $file["user_can"] = $this->user_can;
                $this->load_view("directory_edit", $file);
            } else if ($file_type == "file") {
                $file["error"] = $error;
                $file["admins"] = $this->perms_model->get_user_list($file_id);
                $file["user"] = $this->user;
                $file["user_can"] = $this->user_can;
                $file["data"] = $this->docs_model->get_file_data($file_id);
                if ($file["error"] && isset($_POST["name"])) {
                    $file["unsaved"] = [
                        "name" => htmlspecialchars($_POST["name"]),
                        "slug" => htmlspecialchars(@$_POST["slug"] ?: ""),
                        "data" => htmlspecialchars(@$_POST["data"] ?: ""),
                    ];
                }
                $this->load_view("file_edit", $file);
            } else {
                $this->http->response_code(404);
            }
        } else if ($action == 'history'){
            if (!$this->user_can['read_file']) {
                $this->http->response_code(403);
            }
            if ($file_type == "file") {
                $file["history"] = $this->docs_model->get_history($file_id);

                $file["user_can"] = $this->user_can;
                if ($this->user_can["see_history_detail"] && isset($_GET["id"])) {
                    $edit_id = $_GET["id"];
                    $file["history_item"] = $this->docs_model->get_history_item($file_id, $edit_id);
                    if ($file["history_item"] !== false && $file["history_item"]["action"] == 'edit') {
                        $file["history_diff"] = $this->docs_model->get_history_diff($file_id, $edit_id);
                    }
                } else if (isset($_GET["id"])) {
                    $file["perm_error"] = true;
                }

                $this->load_view("file_history", $file);
            } else {
                $this->http->response_code(404);
            }
        } else {
            if (!$this->user_can['read_file']) {
                $this->http->response_code(403);
            }
            if ($file_type == "directory") {
                $file["data"] = $this->docs_model->get_directory($file_id);
                $file["user_can"] = $this->user_can;
                $this->load_view("directory", $file);
            } else if ($file_type == "file") {
                $file["data"] = $this->docs_model->get_file_data($file_id);
                $file["user_can"] = $this->user_can;
                $this->load_view("file", $file);
            } else {
                $this->http->response_code(404);
            }
        }
    }

    function trash() {
        $user_can = $this->perms_model->get_permissions(0, $this->user);
        if (!$user_can['see_global_trash']) {
            $this->http->response_code(403);
        }

        $error = "";
        $msg = "";
        if (!empty($_POST["restore_file"]) && isset($_POST["file_id"])) {
            $file_id = $_POST["file_id"];
            $recovered = $this->docs_model->recover_file($file_id, $this->user);
            if ($recovered === false) {
                $error = "Could not recover file";
            } else {
                $_SESSION['recovered_file'] = $file_id;
                $this->http->redirect(base_url() . "trash/");
            }
        }

        if (isset($_SESSION['recovered_file'])) {
            $file_id = $_SESSION['recovered_file'];
            unset($_SESSION['recovered_file']);

            $file = $this->docs_model->get_file($file_id);
            if ($file !== false) {
                $msg = ucfirst($file['type']) . ' recovered. See <a href="'
                    . base_url() . 'docs' . $this->docs_model->get_file_path($file['id'])
                    . '"> recovered '
                    . $file['type'] . '</a>.';
            }
        }

        $trash_list = $this->docs_model->get_trash_list();
        $this->load_view('trash', [
            'files' => $trash_list,
            'error' => $error,
            'msg' => $msg
        ]);
    }

}
