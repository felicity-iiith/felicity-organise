<?php

class docs extends Controller {

    function __construct() {
        $this->load_library("cas_lib", "cas");
        $this->cas->forceAuthentication();

        $this->load_model('docs_model');
    }

    function read() {
        $path = func_get_args();
        $file_id = $this->docs_model->get_path_id($path);
        $file_type = $this->docs_model->get_file_type($file_id);

        if($file_type == "directory") {
            $file = $this->docs_model->get_file($file_id);
            $file['data'] = $this->docs_model->get_directory($file_id);
            $this->load_view("directory", $file);
        } else if($file_type == "file") {
            $file = $this->docs_model->get_file($file_id);
            $this->load_view("file", $file);
        } else {
            $this->load_library("http_lib", "http");
            $this->http->err_404();
        }
    }

}
