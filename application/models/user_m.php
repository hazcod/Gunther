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
	//var_dump(password_hash($password, PASSWORD_DEFAULT));        
	//var_dump($hash->password);	
	//var_dump(password_verify($password, $hash->password));
	if ($hash && password_verify($password, $hash->password) == true) {
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
        $user = $this->db->query($query, $login)->getResult();
        if ($user){
		$result = $user[0];
	}
	//var_dump($user);
	return $result;
    }
}
