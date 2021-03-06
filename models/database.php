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
        '4' => 'dashboard.php?title=Browse%20Manuscript',
    );

    protected $messages = array(
        '1' => ' has been uploaded. Please check the list of manuscripts.',
        '2' => ' has been approved!',
        '3' => ' has been rejected! Reason: ',
        '4' => ' has been requested! Click here to view the list of manuscripts being requested.',
        '5' => ' request has been approved!',
        '6' => ' request has been rejected! Reason: ',
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

    protected function getGroupNumberByUserID($id)
    {
        $sql = "SELECT groupNumber FROM groupings WHERE userID = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['groupNumber'];
        } else {
            return 0;
        }
    }

    protected function getGroupNumberBySrCode($srCode)
    {
        $sql = "SELECT 
                g.groupNumber
                FROM user_details u
                LEFT JOIN groupings g ON u.id = g.userID
                WHERE u.srCode = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('s', $srCode);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return $row['groupNumber'];
            }
        } else {
            return 0;
        }
    }

    protected function getUserIdByGroupNumber($groupNumber)
    {
        $sql = "SELECT DISTINCT userID FROM groupings WHERE groupNumber = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('i', $groupNumber);
        $stmt->execute();
        $result = $stmt->get_result();
        $userID = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $userID[] = $row['userID'];
            }
            return $userID;
        } else {
            return 0;
        }
    }

    protected function getUserIdFromGroupings()
    {
        $sql = "SELECT userID FROM groupings";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $userID = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $userID[] = $row['userID'];
            }
            return $userID;
        } else {
            return 0;
        }
    }

    protected function getRole($id)
    {
        $sql = "SELECT role FROM user_details WHERE id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['role'];
        } else {
            return 0;
        }
    }

    protected function getIdByGroupNumber($groupNumber)
    {
        $sql = "SELECT id FROM groupings WHERE groupNumber = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('i', $groupNumber);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row['id'];
            }
            return $data;
        } else {
            return 0;
        }
    }

    protected function getSRCodes($id)
    {

        $sql = "SELECT srCode FROM user_details";
        if (is_array($id)) {
            $ids = implode("','", $id);
            $sql .= " WHERE id IN ('" . $ids . "')";
        } else {
            $sql .= " WHERE id = '$id'";
        }
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row['srCode'];
            }
            return $data;
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
        $col = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $col[] = $row[$column];
            }
        }

        return $col;
    }

    protected function getAuthorByID($manuscriptID)
    {
        $sql = "SELECT author FROM manuscript WHERE id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("i", $manuscriptID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['author'];
    }

    protected function getSRCodeByNames($names)
    {
        $names = "'" . str_replace(",", "','", $names) . "'";
        $sql = "SELECT id FROM user_details WHERE concat_ws(' ', firstName, lastName) IN (" . $names . ")";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $id = [];

        while ($row = $result->fetch_assoc()) {
            $id[] = $row['id'];
        }

        return $id;
    }

    protected function getUserDetails($id)
    {
        $sql = "SELECT
                id, 
                concat_ws(' ', firstName, lastName) as name,
                email
                FROM user_details";

        if (is_array($id)) {
            $sql .= " WHERE id IN (" . implode(',', $id) . ")";
        } else {
            $sql .= " WHERE id = " . $id;
        }

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    protected function lastManuscriptID()
    {
        $sql = "SELECT id FROM manuscript ORDER BY id DESC LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['id'];
    }

    protected function lastUserID()
    {
        $sql = "SELECT id FROM user_details ORDER BY id DESC LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['id'];
    }

    protected function deleteFiles($files)
    {
        $abstract = array_column($files, 'abstract')[0];
        $journal = array_column($files, 'journal')[0];

        unlink($this->directory . $abstract);
        unlink($this->directory . $journal);

        return true;
    }

    protected function createOTP()
    {
        return substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', 6)), 0, 6);
    }

    protected $url = "http://localhost/BatStateU-Malvar%20Thesis%20Repository%20System/";

    // protected $url = "http://www.bsumalvare-library.com/";

    protected $directory = "../assets/uploads/";

    // protected $host = "smtp.gmail.com";

    protected $host = "smtp.gmail.com";

    protected $port = 587;

    protected $emailName = "BatStateU JPLPC-Malvar Thesis Repository and Management System";

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
