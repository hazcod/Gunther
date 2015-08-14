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
        global $settings;
        try {
            // $this refers to the current class
            $this->db = new PDO('sqlite:' . $settings['DB_LOC']);
            // set the error reporting attribute
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	    //error_log('Checking ' . $settings['DB_LOC'] . ' = ' . var_dump(file_exists($settings['DB_LOC'])));
            if (! $this->testDB()) {
		error_log('No active database found, creating at ' . $settings['DB_LOC']);
		$this->needSetup = true;
		$this->setup();
	    }
        } catch(PDOException $e) {
            error_log($e->getMessage());
        }
    }

    private function testDB()
    {
	global $settings;
	return (filesize($settings['DB_LOC']) != 0);
    }

    private function setup()
    {
        // new database, so setup
        // TODO: separate file?
        $queries = array(
            "CREATE TABLE IF NOT EXISTS roles (id INTEGER PRIMARY KEY AUTOINCREMENT, name VARCHAR(10) NOT NULL);",
            "CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY AUTOINCREMENT, login VARCHAR(20) NOT NULL, pass VARCHAR(32) NOT NULL, name TEXT NOT NULL, email TEXT NOT NULL, role SMALLINT REFERENCES roles(id));",
            "CREATE TABLE IF NOT EXISTS req_movies (date DATESTR, file TEXT NOT NULL, user SMALLINT REFERENCES users(id), ip VARCHAR(39), msec DOUBLE);",
            "CREATE TABLE IF NOT EXISTS version (version VARCHAR(10) NOT NULL);",
            "CREATE TABLE IF NOT EXISTS static_cache (id INTEGER PRIMARY KEY AUTOINCREMENT, type VARCHAR(10) NOT NULL, path TEXT NOT NULL, url TEXT NOT NULL);",
	    "CREATE TABLE IF NOT EXISTS movies (id INTEGER PRIMARY KEY AUTOINCREMENT, tvdbid INT NOT NULL, name VARCHAR(30) NOT NULL, description TEXT NOT NULL, quality VARCHAR(15), release VARCHAR(20));",

            "INSERT INTO version(version) VALUES ('1.0.0');",
            "INSERT INTO roles(id, name) VALUES (0, 'Administrator'), (1, 'Regular user');",
            "INSERT INTO users(id, login, name, email, pass, role) VALUES (0,'admin','admin','admin@localhost.local', 'admin:Media:2b952a0aecefebc7facc12d56a3519af', 1);"
        );
        foreach ($queries as $query){
            $this->query($query);
        }
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
