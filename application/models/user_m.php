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
	    if ($hash && password_verify($password, $hash->password) == true) {
            $result = true;
        }
        return $result;
    }

    public function getUserById($id)
    {
        $result = false;
        $query = "
            SELECT login, password, u.id, r.name as role, email, created, lastseen
            FROM users u, roles r
            WHERE (u.role = r.id) AND (u.id = ?);
        ";
        $user = $this->db->query($query, $id)->getRow();
        if ($user){
          $result = $user;
        }
        return $result;
    }
    	
    public function getUserByLogin($login)
    {
        $result = false;
        $query = "
            SELECT login, password, u.id, r.name as role, email, created, lastseen
            FROM users u, roles r
            WHERE (u.role = r.id) AND (login = ?);
        ";
        $user = $this->db->query($query, $login)->getRow();
        if ($user){
		  $result = $user;
	    }
	    return $result;
    }

    public function addUser($user, $pass)
    {
        $result = false;
        $query = "
            INSERT INTO users (login, password, role)
            VALUES (?, ?, 2);";
        $r = $this->db->query($query, array($user, password_hash($pass,PASSWORD_DEFAULT)))->getResult();
        if ($r){
            $result = $r;
        }
        return $result;
    }

    public function delUser($id)
    {
        $query = "DELETE FROM users WHERE (id = ?);";
        $this->db->query($query, $id);
        return true;
    }

    public function getUsers(){
        $result = false;
        $query = "SELECT login, password, u.id, r.name as role, email, created, lastseen
                  FROM users u, roles r
                  WHERE (u.role = r.id)";
        $users = $this->db->query($query)->getResult();
        if ($users){
            $result = $users;
        }
        return $result;
    }
}
