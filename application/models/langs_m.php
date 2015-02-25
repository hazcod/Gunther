<?php
class Langs_m extends Core_db
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'langs';
    }
    
    public function addLang($name, $flag) {
        $query = "INSERT INTO langs (id, name, flag) VALUES ('', ?, ?);";  
        $this->db->query($query, array($name, $flag));
    }
    
    public function deleteLang($id) {
        $query = "DELETE FROM langs WHERE (id = ?);";
        $this->db->query($query, $id);
    } 
    
    public function getLangs()
    {
        $result = false;
        $query =   "SELECT * FROM langs";  
        
        $langs = $this->db->query($query)->getResult();

        if ($langs){
            $result = $langs;
        }

        return $result;
    }
    
}