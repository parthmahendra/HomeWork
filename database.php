<?php

class Database
{

    public $database;

    function __construct()
    {
        $this->database = $this->getConnection();
    }

    function __destruct()
    {
        $this->database->close();
    }

    function exec($query)
    {
        $this->database->exec($query);
    }

    function query($query)
    {
        $result = $this->database->query($query);
        return $result;
    }

    function querySingle($query)
    {
        $result = $this->database->querySingle($query, true);
        return $result;
    }

    function prepare($query)
    {
        return $this->database->prepare($query);
    }

    function escapeString($string)
    {
        return $this->database->escapeString($string);
    }

    private function getConnection()
    {
        $conn = new SQLite3('test.db');
        return $conn;
    }
}