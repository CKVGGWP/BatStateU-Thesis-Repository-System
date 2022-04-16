<?php

$directory = dirname(__FILE__);
require_once $directory . '/../assets/vendor/autoload.php';

require('credentials.php');
require('functions.php');

date_default_timezone_set('Asia/Manila');

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
        '2' => 'dashboard.php?title=Dashboard',
        '3' => 'dashboard.php?title=Pending Manuscripts',
    );

    protected $messages = array(
        '1' => ' has been uploaded. Please check the list of manuscripts.',
        '2' => ' has been approved!',
        '3' => ' has been rejected! Reason: ',
        '4' => ' request has been approved!',
        '5' => ' request has been rejected! Reason: ',
    );

    protected function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    protected function createToken()
    {
        return md5(uniqid(rand(), true));
    }

    protected function getID($srCode)
    {
        $sql = "SELECT id FROM user_details WHERE srCode = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('s', $srCode);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['id'];
        } else {
            return 0;
        }
    }

    protected function getSRCode($table, $column, $where, $value)
    {
        $sql = "SELECT $column FROM $table WHERE $where = ?";
        $stmt = $this->connect()->prepare($sql);

        if (is_int($value)) {
            $stmt->bind_param('i', $value);
        } else {
            $stmt->bind_param('s', $value);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row[$column];
        } else {
            return 0;
        }
    }

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
