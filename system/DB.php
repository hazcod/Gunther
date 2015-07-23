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
        } catch(PDOException $e) {
            error_log($e->getMessage());
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
