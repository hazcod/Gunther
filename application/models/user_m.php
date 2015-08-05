<?php
class User_m extends Core_db
{
   private function writeAuthFile()
   {
   	$str = "";
   	foreach ($user as $this->getUsers()){
   		$str .= $user->pass . "\n";
   	}
   	file_put_contents($settings['AUTH_DIGEST_FILE'], $str, LOCK_EX);
   }
	
    public function isValid($login, $password)
    {
        $result = false;
        $user = $this->getUserByLogin($login);
	$password = hash('md5', $login . ':Media:' . $password);

	if ($user && strcmp($password, $user->password) == 0) {
            $result = true;
        }
	return $result;
    }

    public function getUserById($id)
    {
        $result = false;

	$query = "SELECT * FROM users WHERE (id = ?);";
	$user = $this->db->query($query, $id);

	if ($user){
		$result = $user->getRow();
	}

        return $result;
    }

    public function getUserByLogin($login)
    {
        $result = false;

	$query = "SELECT * FROM users WHERE (login = ?);";
	$user = $this->db->query($query, $login);

	if ($user){
		$result = $user->getRow();
	}

	return $result;
    }

    public function addUser($login, $pass, $name, $email, $role)
    {
	$role = $this->db->query("SELECT id FROM roles WHERE (name = ?);", $role);
	if ($role){
		$role = $role->getRow()->name;
		$pass_str = hash('md5', $login . ':Media:' . $pass);
        	$query = "INSERT INTO users(login, name, email, pass, role) VALUES (?,?,?,?,?);";
		$this->db->query($query, array($login, $name, $email, $pass_str, $role));

		$user = $this->db->query("SELECT * FROM users WHERE (login = ?);", $login);
		$result = ($user != false);
		if ($result){
			$this->writeAuthFile();
		}
	} else {
		error_log("Could not find role '" . $role ."' when adding user " . $login);
	}
        return $output;
    }

    public function delUser($id)
    {
        $query = "DELETE FROM users WHERE (id = ?);";
	$this->db->query($query, $id);

	$user = $this->db->query("SELECT * FROM users WHERE (id = ?);", $id);
	$result = ($user == false);
	if ($result){
		$this->writeAuthFile();
	}
	return $result;
    }

    public function getUsers(){
        $result = false;

	$query = "SELECT * FROM users;";
	$users = $this->db->query($query);

	if ($users){
		$result = $users->getResult();
	}

        return $result;
    }
}
