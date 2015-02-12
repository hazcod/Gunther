<?php
class User_m extends Core_db
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'users';
    }
    
       public function isValid($login, $password)
    {
        $result = false;
        $query = "
            SELECT password
            FROM users
		    WHERE login = ?;
        ";
        $hash = $this->db->query($query, $login)->getRow();
        if ($hash != false && strcmp($hash->password, $password) == 0) {
            $result = true;
        }
        return $result;
    }
    	
       public function getUserByLogin($login)
    {
        $result = false;
        $query = "
            SELECT *
            FROM users
		 WHERE login = ?;
        ";
        $hash = $this->db->query($query, $login)->getRow();
        return $result;
    }
}