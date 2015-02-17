<?php

abstract class Core_db
{
    protected $db;
    protected $table;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

}
