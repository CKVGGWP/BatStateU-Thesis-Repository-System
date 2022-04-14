<?php

$directory = dirname(__FILE__);
require_once $directory . '/../assets/vendor/autoload.php';

require('credentials.php');

class Database
{
    protected $typeID = array(
        '1' => 'Pending Manuscript',
        '2' => 'Approved Manuscript',
        '3' => 'Rejected Manuscript',
        '4' => 'Pending Request',
        '5' => 'Approved Request',
        '6' => 'Rejected Request',
    );

    protected $redirect = array(
        '1' => 'dashboard.php?title=View Request',
    );

    protected $messages = array(
        '1' => ' has been uploaded. Please check the list of manuscripts.',
    );

    protected $url = "http://localhost/BatStateU-Malvar%20Thesis%20Repository%20System/";

    protected function connect()
    {
        $this->conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        } else {
            return $this->conn;
        }
    }
}
