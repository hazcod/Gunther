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
        if ($hash != false && password_verify($password, $hash->password) == true) {
            $result = true;
        }
        return $result;
    }
    	
       public function getUserByLogin($login)
    {
        $result = false;
        $query = "
            SELECT *
            FROM users u, roles r
            WHERE (u.role = r.id) AND (login = ?);
        ";
        $hash = $this->db->query($query, $login)->getRow();
        return $result;
    }
}