<?php

class LoginRegister extends Database
{
    public function login($email, $password)
    {
        if ($this->emailExists($email) != true) {
            return 1;
            exit();
        }

        if ($this->isVerified($email) != true) {
            return 2;
            exit();
        }

        if ($this->checkPassword($email, $password) != true) {
            return 3;
            exit();
        }

        return $this->setSession($email);
    }

    public function register($data)
    {
        if ($this->emailExists($data['email']) == true) {
            return 1;
            exit();
        }

        if (strlen($data['srCode']) < 7 || strlen($data['srCode']) > 10) {
            return 2;
            exit();
        }

        if ($this->insertData($data) == false) {
            return 3;
        } else {
            return 4;
        }
    }

    public function verify($tokenKey, $srCode)
    {
        return $this->verifyEmail($tokenKey, $srCode);
    }

    private function emailExists($email)
    {
        $sql = "SELECT * FROM user_details WHERE email = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function isVerified($email)
    {
        $sql = "SELECT * FROM user_details WHERE email = ? AND verifyStatus = 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function selectPassword($email)
    {
        $sql = "SELECT password FROM user_details WHERE email = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['password'];
        }
    }

    private function checkPassword($email, $password)
    {
        $hashedPassword = $this->selectPassword($email);
        if (password_verify($password, $hashedPassword)) {
            $password = $hashedPassword;
            $sql = "SELECT * FROM user_details WHERE email = ? AND password = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bind_param("ss", $email, $password);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return true;
            }
        } else {
            return false;
        }
    }

    private function getSessionID($email)
    {
        $sql = "SELECT srCode FROM user_details WHERE email = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['srCode'];
        }
    }

    private function setSession($email)
    {
        session_start();
        $_SESSION['srCode'] = $this->getSessionID($email);
    }

    private function insertData($data)
    {
        $newPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO user_details (srCode, email, firstName, middleName, lastName, departmentID, campusID, password, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("sssssiiss", $data['srCode'], $data['email'], $data['firstName'], $data['middleName'], $data['lastName'], $data['department'], $data['campus'], $newPassword, '0');
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return $this->insertToken($stmt->insert_id, $data['email'], $data['firstName'] . " " . $data['lastName'], $data['srCode']);
        } else {
            return false;
        }
    }

    private function insertToken($userID, $email, $name, $srCode)
    {
        $purpose = 'Email';
        $token = bin2hex(random_bytes(32));
        $sql = "INSERT INTO user_token (userId, tokenKey, purpose) VALUES (?, ?, ?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("iss", $userID, $token, $purpose);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return $this->sendEmail($email, $token, $name, $srCode);
        } else {
            return false;
        }
    }

    private function sendEmail($email, $tokenKey, $name, $srCode)
    {
        $to = $email;
        $subject = 'Welcome to the Batangas State University JPLPC-Malvar Thesis Repository System';
        $message = '
        <html>
        <head>
        <title>You have successfully created an account!</title>
        </head>
        <body>
        <p>Hi' . $name . ' ,</p>
        <p>Please click the link below to verify your email address.</p>
        <p><a href="http://localhost/BatStateU-Malvar Thesis Repository System/verify.php?tokenKey=' . $tokenKey . '&srCode=' . $srCode . '">Verify</a></p>
        <p>Thank you.</p>
        <p>This is a system-generated message. Please do not reply!</p>
        </body>
        </html>
        ';
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From:
        <
        ';

        if (mail($to, $subject, $message, $headers)) {
            return true;
        } else {
            return false;
        }
    }

    private function verifyEmail($srCode, $tokenKey)
    {
        $sql = "SELECT u.id 
                FROM user_details u 
                INNER JOIN user_token t ON u.id = t.userId 
                WHERE u.srCode = ?
                AND t.tokenKey = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("ss", $srCode, $tokenKey);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $userID = $row['id'];
            return $this->updateStatus($userID);
        } else {
            return 1;
        }
    }

    private function updateStatus($userID)
    {
        $sql = "UPDATE user_details SET verifyStatus = 1 WHERE id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("i", $userID);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return 2;
        } else {
            return 3;
        }
    }
}
