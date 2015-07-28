<?php

class DB
{
    private static $instance = null;
    protected $db;
    private $log;
    private $stmt;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // on instantination: build the connection
    private function __construct()
    {
        try {
            // $this refers to the current class
            $this->db = new PDO('sqlite:test.db');
            // set the error reporting attribute
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    if ($this->query('SELECT id FROM users LIMIT 1;')->getRow() == false){
		error_log('Setting up database..');
		$this->setup();
	    }
	} catch(PDOException $e) {
            error_log('DB ERROR: ' . $e->getMessage());
        }
    }

   private function setup()
   {

	$this->query('CREATE TABLE IF NOT EXISTS roles (id INTEGER PRIMARY KEY AUTOINCREMENT, name VARCHAR(10) NOT NULL);');
	$this->query('CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY AUTOINCREMENT, login VARCHAR(20) NOT NULL, pass VARCHAR(32) NOT NULL, name TEXT NOT NULL, email TEXT NOT NULL, role SMALLINT REFERENCES roles(id));');
	$this->query('CREATE TABLE IF NOT EXISTS req_movies (date DATESTR, file TEXT NOT NULL, user SMALLINT REFERENCES users(id));');
	
	$this->query("INSERT INTO roles(id, name) VALUES ('', 'administrator');");
	$this->query("INSERT INTO users(id, login, name, email, pass, role) VALUES ('','admin','admin','admin@localhost.local', 'admin:Media:2b952a0aecefebc7facc12d56a3519af', 1);");
   }

    public function query($sql, $arguments = array())
    {
        if (!is_array($arguments)) {
            $arguments = array($arguments);
        }

        try {
            $this->stmt = $this->db->prepare($sql);
            $this->stmt->execute($arguments);
            $this->stmt->setFetchMode(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {

            error_log($e->getMessage());
        }

        return $this;
    }

    public function getResult()
    {
        $result = array();

        if ($this->stmt) {
            // as long as there are rows, add them to the data-output array
            while ($row = $this->stmt->fetchObject()) {
                $result[] = $row;
            }
        }

        return $result;
    }

    public function getRow()
    {
        $row = false;

        if ($this->stmt) {
            $row = $this->stmt->fetchObject();
        }

        return $row;
    }
}
