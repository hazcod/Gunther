<?php

class Cache extends Core_controller
{
    public function __construct()
    {
        parent::__construct(false);
        
        $whitelist = array(
            '127.0.0.1',
            '::1',
            'localhost'
        );

        if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
            // only allow localhost
            header("HTTP/1.1 401 Unauthorized");
            die();
        }
    }

    public function index()
    {
        $this->mediamodel->fillCache();
        echo 'Caches flushed.';
        return true;
    }


}
