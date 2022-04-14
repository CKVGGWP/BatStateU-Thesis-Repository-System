<?php

require('credentials.php');

class Database
{
    private $host = DB_HOST;
    private $dbName = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;

    protected function connect()
    {
        $this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->dbName);
        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        } else {
            return $this->conn;
        }
    }
}
