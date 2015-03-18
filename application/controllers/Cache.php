<?php

class Cache extends Core_controller
{
    public function __construct()
    {
        parent::__construct(false);
    }

    public function index()
    {
        $this->mediamodel->fillCache();
        echo 'Caches flushed.';
        return true;
    }


}
