<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Information extends Database
{
    public function getCampuses($id = '')
    {
        $sql = "SELECT * FROM campus";
        if ($id != '') {
            $sql .= " WHERE id NOT IN ('$id')";
        }
        $sql .= " ORDER BY campusName ASC";
        $result = $this->connect()->query($sql);
        $campuses = [];
        while ($row = $result->fetch_assoc()) {
            $campuses[] = $row;
        }
        return $campuses;
    }

    public function getDeptByCampus($id)
    {
        $option = '';
        $sql = "SELECT * FROM department WHERE campusID = ? ORDER BY departmentName ASC";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $option .= '<option value="' . $row['id'] . '">' . $row['departmentName'] . '</option>';
            }
        } else {
            $option .= '<option value="">No department found</option>';
        }

        return json_encode($option);
    }

    public function getUserBySession($id)
    {
        $sql = "SELECT *, c.id AS campID, d.id AS deptID FROM user_details u 
                LEFT JOIN campus c ON c.id = u.campusID 
                LEFT JOIN department d ON d.id = u.departmentID
                WHERE u.srCode = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        } else {
            return false;
        }
    }

    public function updateInfo($data)
    {
        if ($this->emailBySRCode($data['srCode']) != false) {
            $currentEmail = $this->emailBySRCode($data['srCode']);
            if ($currentEmail['email'] != $data['email']) {
                if ($this->emailExists($data['email']) == true) {
                    return 1;
                    exit();
                }
                return $this->updateFunction($data, 'Email');
            }
            return $this->updateFunction($data);
        }
    }

    public function changePassword($current, $new, $srCode)
    {
        if ($this->verifyPassword($current, $srCode) == false) {
            return 1;
            exit();
        }

        return $this->updatePassword($new, $srCode);
    }

    private function countNotification($srCode)
    {
        $id = $this->getID($srCode);
        $sql = "SELECT 
                COUNT(n.id) AS nums
                FROM notification n 
                LEFT JOIN user_details u ON u.id = n.userID 
                WHERE u.id = ? 
                AND nRead = 0";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['nums'];
        } else {
            return 0;
        }
    }

    public function getNotification($srCode, $action = "")
    {
        if ($action != "") {
            $this->updateNotification($srCode);
        }
        $id = $this->getID($srCode);
        $sql = "SELECT n.type, n.notifMessage, n.dateReceived, n.redirect FROM notification n
                LEFT JOIN user_details u ON u.id = n.userID 
                WHERE n.userID = ? ORDER BY n.nRead ASC, n.dateReceived DESC";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $notif = "";

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $notif .= '<li class="notification-item">';
                $notif .= '<i class="fa-regular fa-file-pdf text-danger"></i>';
                $notif .= '<a href="' . $row['redirect'] . '">';
                $notif .= "<div>";
                $notif .= "<h4>" . $row['type'] . "</h4>";
                $notif .= "<p>" . $row['notifMessage'] . "</p>";
                $notif .= "<p>" . read_dateTime($row['dateReceived']) . "</p>";
                $notif .= "</div>";
                $notif .= "</a>";
                $notif .= "<li>";
                $notif .= "<hr class='dropdown-divider'>";
                $notif .= "</li>";
            }
        } else {
            $notif .= '<li class="notification-item d-flex align-items-center justify-content-center">';
            $notif .= '<i class="fa-regular fa-bell"></i>';
            $notif .= '<div>';
            $notif .= '<h4>You have no notifications yet!</h4>';
            $notif .= '</div>';
            $notif .= '</li>';
            $notif .= "<li>";
            $notif .= "<hr class='dropdown-divider'>";
            $notif .= "</li>";
        }

        $header = "You have " . $this->countNotification($srCode) . " new notification(s)";
        $header .= ($this->countNotification($srCode) > 0) ? '<a href="#" id="markAllBTN"><span class="badge rounded-pill bg-info p-2 ms-2">Mark all as Read</span></a>' : '';

        $data = array(
            "countHeader" => $header,
            "countNotifications" => $this->countNotification($srCode),
            "notifications" => $notif
        );

        return json_encode($data);
    }

    public function updateNotification($srCode)
    {
        $id = $this->getID($srCode);
        $sql = "UPDATE notification SET nRead = 1 WHERE userID = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function insertIP($ip)
    {
        if ($this->checkIPExists($ip) == true) {
            return false;
            exit();
        }

        $sql = "INSERT INTO websiteTraffic (ipAddress) VALUES (?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('s', $ip);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getIPChart()
    {
        $sql = "SELECT COUNT(ipAddress) AS nums FROM websiteTraffic";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $count = $row['nums'];
        } else {
            $count = 0;
        }

        return $count;
    }

    public function getAllUsers()
    {
        $sql = "SELECT COUNT(*) AS `count`,
                c.campusName 
                FROM user_details u 
                LEFT JOIN campus c ON u.campusID = c.id
                GROUP BY campusID";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $count[] = $row['count'];
                $campus[] = $row['campusName'];
            }
            $total[] = array_sum($count);
            $data = array($count, $campus, $total);
            return json_encode($data);
        } else {
            return false;
        }
    }

    public function getUserByCampus($campID)
    {
        $sql = "SELECT 
                CONCAT(firstName, ' ', middleName, ' ', lastName) AS fullName                FROM user_details
                WHERE campusID = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('i', $campID);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    private function checkIPExists($ip)
    {
        $sql = "SELECT * FROM websiteTraffic WHERE ipAddress = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('s', $ip);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function emailBySRCode($srCode)
    {
        $sql = "SELECT email FROM user_details WHERE srCode = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('s', $srCode);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        } else {
            return false;
        }
    }

    private function verifyPassword($current, $srCode)
    {
        $sql = "SELECT password FROM user_details WHERE srCode = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('i', $srCode);
        $stmt->execute();
        $result = $stmt->get_result();

        $row = $result->fetch_assoc();
        if (password_verify($current, $row['password'])) {
            return true;
        } else {
            return false;
        }
    }

    private function updatePassword($new, $srCode)
    {
        $newPass = $this->hashPassword($new);
        $sql = "UPDATE user_details SET password = ? WHERE srCode = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('si', $newPass, $srCode);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $user = $this->getUserBySession($srCode);
            return $this->sendEmail($user['email'], '', $user['firstName'] . ' ' . $user['lastName'], $srCode, 'change');
        } else {
            return 2;
        }
    }

    private function emailExists($email)
    {
        $sql = "SELECT * FROM user_details WHERE email = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function updateFunction($data, $purpose = '')
    {
        $sql = "UPDATE user_details u";
        if ($purpose == 'Email') {
            $sql .= " LEFT JOIN user_token t ON t.userID = u.id";
        }

        $sql .= " SET u.email = ?, u.firstName = ?, u.middleName = ?, u.lastName = ?, u.campusID = ?, u.departmentID = ?";

        if ($purpose == 'Email') {
            $sql .= ", u.verifyStatus = '0', t.tokenKey = ?";
        }

        $sql .= " WHERE u.srCode = ?";

        if ($purpose == 'Email') {
            $sql .= " AND t.purpose = 'Email'";
        }

        $stmt = $this->connect()->prepare($sql);
        if ($purpose == 'Email') {
            $token = $this->createToken();
            $stmt->bind_param('ssssiiss', $data['email'], $data['firstName'], $data['middleName'], $data['lastName'], $data['campus'], $data['department'], $token, $data['srCode']);
        } else {
            $stmt->bind_param('ssssiis', $data['email'], $data['firstName'], $data['middleName'], $data['lastName'], $data['campus'], $data['department'], $data['srCode']);
        }

        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            if ($purpose == 'Email') {
                $this->sendEmail($data['email'], $token, $data['firstName'] . ' ' . $data['lastName'], $data['srCode'], "verify");
            } else {
                return 3;
            }
        } else {
            return 2;
        }
    }

    private function sendEmail($email, $tokenKey = '', $name, $srCode, $type)
    {
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = 0; //SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = EMAIL;                     //SMTP username
            $mail->Password   = PASSWORD;                               //SMTP password
            $mail->SMTPSecure = "tls";            //Enable implicit TLS encryption
            $mail->Port       = 587;                                  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom(EMAIL, 'BatStateU JPLPC-Malvar Thesis Repository and Management System');
            $mail->addAddress($email);     //Add a recipient

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            if ($type == "verify") {
                $mail->Subject = 'Account Verification - BatStateU-Malvar Thesis Repository System';
                $email_header = "<h3>Hi " . "<b>" . $name . "</b>" . ',</h3>';
                $email_text = "<span>This is to inform you that you have successfully changed your email! Please <a href='" . $this->url . "verify.php?tokenKey=" . $tokenKey . "&srCode=" . $srCode . "'>click here</a> to verify your new email address.</span><br><br>";
                $email_footer = "This is a system generated message. Please do not reply.";
                $email_template = $email_header . $email_text . $email_footer;
            } else if ($type == "change") {
                $mail->Subject = 'Password Has Been Changed - BatStateU-Malvar Thesis Repository System';
                $email_header = "<h3>Hi " . "<b>" . $name . "</b>" . ',</h3>';
                $email_text = "<span>This is to inform you that you have recently changed your password. If you did not do this change, contact us immediately.</span><br><br>";
                $email_footer = "This is a system generated message. Please do not reply.";
                $email_template = $email_header . $email_text . $email_footer;
            }

            $mail->Body = $email_template;

            if ($mail->send()) {
                return 4;
            } else {
                return $mail->ErrorInfo;
            }
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
