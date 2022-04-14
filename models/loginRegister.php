<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require('../assets/vendor/autoload.php');

class LoginRegister extends Database
{
    private $url = "http://localhost/BatStateU-Malvar%20Thesis%20Repository%20System/";

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

        if ($this->checkSRCode($data['srCode']) == true) {
            return 5;
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
        if ($this->checkSRCode($srCode) != true) {
            return 4;
            exit();
        }

        return $this->verifyEmail($tokenKey, $srCode);
    }

    public function resetPassword($email)
    {
        if ($this->emailExists($email) != true) {
            return 1;
            exit();
        }

        if ($this->insertPassToken($this->getID($email))) {
            return 3;
        } else {
            return 2;
        }
    }

    public function changePassword($data)
    {
        if ($this->checkSRCode($data['srCode']) != true) {
            return 1;
            exit();
        }

        if ($this->validateToken($data['tokenKey'], $data['srCode']) != true) {
            return 2;
            exit();
        }

        if ($this->validatePassword($data['newPassword'], $data['srCode']) == true) {
            return 3;
            exit();
        }

        if ($this->updatePassword($this->hashPassword($data['newPassword']), $data['srCode']) == false) {
            return 4;
            exit();
        } else {
            return 5;
        }
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

    private function checkSRCode($srCode)
    {
        $sql = "SELECT * FROM user_details WHERE srCode = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("s", $srCode);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function validateToken($tokenKey, $srCode)
    {
        $sql = "SELECT * FROM user_details u 
                INNER JOIN user_token t ON u.id = t.userId 
                WHERE u.srCode = ? AND t.tokenKey = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("ss", $srCode, $tokenKey);
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

    private function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    private function createToken()
    {
        return md5(uniqid(rand(), true));
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

    private function validatePassword($password, $srCode)
    {
        $sql = "SELECT password FROM user_details WHERE srCode = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("s", $srCode);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                return true;
            } else {
                return false;
            }
        }
    }

    private function getSessionID($email)
    {
        $sql = "SELECT srCode, role FROM user_details WHERE email = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        }
    }

    private function setSession($email)
    {
        session_start();
        $sessionID = $this->getSessionID($email);
        $_SESSION['srCode'] = $sessionID['srCode'];
        $_SESSION['role'] = $sessionID['role'];
    }

    private function insertData($data)
    {
        $newPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO user_details (srCode, email, firstName, middleName, lastName, departmentID, campusID, password, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("ssssssss", $data['srCode'], $data['email'], $data['firstName'], $data['middleName'], $data['lastName'], $data['department'], $data['campus'], $newPassword);
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
        $token = $this->createToken();
        $sql = "INSERT INTO user_token (userId, tokenKey, purpose) VALUES (?, ?, ?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("iss", $userID, $token, $purpose);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return $this->sendEmail($email, $token, $name, $srCode, "verify");
        } else {
            return false;
        }
    }

    private function sendEmail($email, $tokenKey, $name, $srCode = '', $type)
    {
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = 0; //SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'otrms.batstateu@gmail.com';                     //SMTP username
            $mail->Password   = 'batstateu12345';                               //SMTP password
            $mail->SMTPSecure = "tls";            //Enable implicit TLS encryption
            $mail->Port       = 587;                                  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('otrms.batstateu@gmail.com', 'BatStateU-Malvar Thesis Repository System');
            $mail->addAddress($email);     //Add a recipient

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            if ($type == "verify") {
                $mail->Subject = 'Account Verification - BatStateU-Malvar Thesis Repository System';
                $email_header = "<h3>Hi " . "<b>" . $name . "</b>" . ',</h3>';
                $email_text = "<span>You have successfully created an account! Please <a href='" . $this->url . "index.php?title=Verify Account&tokenKey=" . $tokenKey . "&srCode=" . $srCode . "'>click here</a> to verify your email address.</span><br><br>";
                $email_footer = "This is a system generated message. Please do not reply.";
                $email_template = $email_header . $email_text . $email_footer;
                $mail->Body = $email_template;
            } else if ($type == "reset") {
                $mail->Subject = 'Password Reset - BatStateU-Malvar Thesis Repository System';
                $email_header = "<h3>Hi " . "<b>" . $name . "</b>" . ',</h3>';
                $email_text = "<span>You have requested to reset your password. Please <a href='" . $this->url . "index.php?title=Reset Password&tokenKey=" . $tokenKey . "&srCode=" . $srCode . "'>click here</a> to reset your password.</span><br><br>";
                $email_footer = "This is a system generated message. Please do not reply.";
                $email_template = $email_header . $email_text . $email_footer;
                $mail->Body = $email_template;
            }

            if ($mail->send()) {
                return true;
            } else {
                return $mail->ErrorInfo;
            }
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    private function verifyEmail($tokenKey, $srCode)
    {
        $sql = "SELECT u.id 
                FROM user_details u 
                INNER JOIN user_token t ON u.id = t.userId 
                WHERE u.srCode = ? AND t.tokenKey = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("ss", $srCode, $tokenKey);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $userID = $row['id'];
            return $this->checkStatus($userID);
        } else {
            return 1;
        }
    }

    private function checkStatus($userID)
    {
        $sql = "SELECT * FROM user_details WHERE id = ? AND verifyStatus = 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return 5;
        } else {
            return $this->updateStatus($userID);
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

    private function getID($email)
    {
        $sql = "SELECT id, firstName, lastName, email, srCode FROM user_details WHERE email = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        }
    }

    private function checkToken($id, $purpose)
    {
        $sql = "SELECT * FROM user_token WHERE userId = ? AND purpose = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("is", $id, $purpose);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function insertPassToken($data)
    {
        $purpose = 'Password';
        $token = $this->createToken();

        if ($this->checkToken($data['id'], $purpose)) {
            return $this->updateToken($data, $token, $purpose);
        } else {
            $sql = "INSERT INTO user_token (userId, tokenKey, purpose) VALUES (?, ?, ?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bind_param("iss", $data['id'], $token, $purpose);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                return $this->sendEmail($data['email'], $token, $data['firstName'] . ' ' . $data['lastName'], $data['srCode'], "reset");
            } else {
                return 2;
            }
        }
    }

    private function updateToken($data, $token, $purpose)
    {
        $sql = "UPDATE user_token SET tokenKey = ? WHERE userID = ? AND purpose = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("sis", $token, $data['id'], $purpose);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return $this->sendEmail($data['email'], $token, $data['firstName'] . ' ' . $data['lastName'], $data['srCode'], "reset");
        } else {
            return 2;
        }
    }

    private function updatePassword($hashedPassword, $srCode)
    {
        $sql = "UPDATE user_details SET password = ? WHERE srCode = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("ss", $hashedPassword, $srCode);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
}
