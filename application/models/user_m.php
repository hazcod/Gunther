<?php
class User_m
{
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
        $users = $this->getUsers();

        foreach ($users as $user){
            if (strcmp($user->id, $id) == 0){
                $result = $user;
                break;
            }
        }

        return $result;
    }
    	
    public function getUserByLogin($login)
    {
        $result = false;
        $users = $this->getUsers();

        foreach ($users as $user){
            if (strcmp($user->login, $login) == 0){
                $result = $user;
                break;
            }
        }

	    return $result;
    }

    public function addUser($user)
    {
        $output = shell_exec('scripts/addUser.sh ' . $this->settings['AUTH_DIGEST_FILE'] . ' ' . $user);
        $output = str_replace("\n", "", $output);
        return $output;
    }

    public function delUser($id)
    {
        shell_exec('scripts/delUser.sh ' . $this->settings['AUTH_DIGEST_FILE'] . ' ' . $id);
        return true;
    }

    public function getUsers(){
        $result = array();

        $handle = fopen($this->settings['AUTH_DIGEST_FILE'], "r");
        if ($handle) {
            $i=0;
            while (($line = fgets($handle)) !== false) {
                $parts = explode(':', $line);
                $result[] = (object) array(
                    'login' => $parts[0],
                    'password' => str_replace(array("\r", "\n"), '', $parts[2]),
                    'id' => $i,
                );  
                $i++;            
            }
            fclose($handle);
        } else {
            error_log('Could not open digest file at ' . $this->settings['AUTH_DIGEST_FILE']);
        }
        return $result;
    }
}
