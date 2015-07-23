<?php
abstract class Core_db
{
    protected $db;
    protected $table;
    public function __construct()
    {
        $this->db = DB::getInstance();
    }
    public function updatePassword($login, $password)
    {
        $query = "UPDATE admins SET password = '$password' where login = '$login'";
        return $this->db->query($query);
    }
}
