<?php

class docs extends Controller {

    function __construct() {
        $this->load_library("cas_lib", "cas");
        $this->cas->forceAuthentication();
    }

}
