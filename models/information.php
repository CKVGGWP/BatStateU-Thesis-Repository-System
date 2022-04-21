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

    public function getProgByDept($id, $purpose = '')
    {
        $sql = "SELECT * FROM program WHERE deptID = ? ORDER BY programName ASC";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $prog = [];
        $option = '';

        if ($purpose == "options") {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $option .= '<option value="' . $row['id'] . '">' . $row['programName'] . '</option>';
                }
            } else {
                $option .= '<option value="">No Program found</option>';
            }

            return json_encode($option);
        } else if ($purpose == "") {
            while ($row = $result->fetch_assoc()) {
                $prog[] = $row;
            }
            return $prog;
        }
    }
    public function getDeptByCampus($id, $purpose = '')
    {
        $sql = "SELECT * FROM department WHERE campusID = ? ORDER BY departmentName ASC";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $dept = [];
        $option = '';

        if ($purpose == "options") {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $option .= '<option value="' . $row['id'] . '">' . $row['departmentName'] . '</option>';
                }
            } else {
                $option .= '<option value="">No department found</option>';
            }

            return json_encode($option);
        } else if ($purpose == "") {
            while ($row = $result->fetch_assoc()) {
                $dept[] = $row;
            }
            return $dept;
        }
    }

    public function getProgram($id)
    {
        $sql = "SELECT * FROM program WHERE deptID = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $option = '';

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $option .= '<option value="' . $row['id'] . '">' . $row['programName'] . '</option>';
            }
        } else {
            $option .= '<option value="">No program found</option>';
        }

        return json_encode($option);
    }

    public function getUsersByDeptProgram($dept, $program)
    {
        $sql = "SELECT concat_ws(' ', firstName, lastName) AS fullName FROM user_details WHERE departmentID = ? AND programID = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('ii', $dept, $program);
        $stmt->execute();
        $result = $stmt->get_result();
        $options = '';

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $options .= '<option value="' . $row['fullName'] . '">' . $row['fullName'] . '</option>';
            }
        } else {
            $options .= '<option value="">No user found</option>';
        }

        return json_encode($options);
    }

    public function getUserBySession($id)
    {
        $sql = "SELECT *, 
                c.id AS campID, 
                d.id AS deptID, 
                p.id AS programID 
                FROM user_details u 
                LEFT JOIN campus c ON c.id = u.campusID 
                LEFT JOIN department d ON d.id = u.departmentID
                LEFT JOIN program p ON p.id = u.programID
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

    public function createAdmin($data)
    {
        if ($this->emailExists($data['email']) == true) {
            return 1;
            exit();
        }

        if ($this->checkIDExists($data['id']) == true) {
            return 3;
            exit();
        }

        return $this->insertAdminAccount($data);
    }

    public function changePassword($current, $new, $srCode)
    {
        if ($this->verifyPassword($current, $srCode) == false) {
            return 1;
            exit();
        }

        return $this->updatePassword($new, $srCode);
    }

    public function checkAdmin($srCode)
    {
        if (in_array($srCode, $this->getAdminSRCode())) {
            return true;
        } else {
            return false;
        }
    }

    public function checkManuscript($srCode)
    {
        $id = $this->getID($srCode);
        $manuscriptID = $this->getManuscript($id);
        $status = $this->checkManuscriptStatus($manuscriptID);

        return ($status == 0) ? 'Pending' : (($status == 1) ? 'Approved' : 'Rejected');
    }

    private function getManuscript($id)
    {
        $sql = "SELECT 
                g.manuscriptID 
                FROM groupings g
                LEFT JOIN manuscript m ON m.id = g.manuscriptID 
                WHERE g.userID = ? AND m.status != '2'";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['manuscriptID'];
        } else {
            return false;
        }
    }

    private function checkManuscriptStatus($manuscriptID)
    {
        $sql = "SELECT status FROM manuscript WHERE id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('i', $manuscriptID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['status'];
        } else {
            return 3;
        }
    }

    private function insertAdminAccount($data)
    {
        $password = $this->hashPassword($data['password']);
        $sql = "INSERT INTO user_details (srCode, email, firstName, middleName, lastName, campusID, password, role) VALUES (?, ?, ?, ?, ?, ?, ?, '1')";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('sssssis', $data['id'], $data['email'], $data['firstName'], $data['middleName'],  $data['lastName'], $data['campus'], $password);
        $stmt->execute();
        $last_id = $stmt->insert_id;

        if ($last_id > 0) {
            return $this->insertNewToken($last_id, $data['email'], $data['firstName'] . ' ' . $data['lastName'], $data['id']);
        } else {
            return false;
        }
    }

    private function insertNewToken($lastID, $email, $name, $srCode)
    {
        $token = $this->createToken();
        $sql = "INSERT INTO user_token (userId, tokenKey, purpose) VALUES (?, ?, 'Email')";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('is', $lastID, $token);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return $this->sendEmail($email, $token, $name, $srCode, "Create Account");
        } else {
            return false;
        }
    }

    private function checkIDExists($id)
    {
        $sql = "SELECT * FROM user_details WHERE srCode = ?";
        $stmt = $this->connect()->prepare($sql);
        if (is_int($id)) {
            $stmt->bind_param('i', $id);
        } else {
            $stmt->bind_param('s', $id);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function getAdminSRCode()
    {
        $sql = "SELECT srCode FROM user_details WHERE role = '1'";
        $result = $this->connect()->query($sql);
        $id = [];

        while ($row = $result->fetch_assoc()) {
            $id[] = $row;
        }

        return $id;
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
        }
        // else {
        //     $notif .= '<li class="notification-item d-flex align-items-center justify-content-center">';
        //     // $notif .= '<i class="fa-regular fa-bell"></i>';
        //     // $notif .= '<div>';
        //     // $notif .= '<h4>You have no notifications yet!</h4>';
        //     // $notif .= '</div>';
        //     $notif .= '</li>';
        //     $notif .= "<li>";
        //     $notif .= "<hr class='dropdown-divider'>";
        //     $notif .= "</li>";
        // }

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

        $sql = "INSERT INTO websitetraffic (ipAddress) VALUES (?)";
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
        $sql = "SELECT COUNT(ipAddress) AS nums FROM websitetraffic";
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

    public function getAllManuscripts()
    {
        $sql = "SELECT * FROM department WHERE campusID = 3";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $departmentId[] = $row['id'];
                $departmentName[] = $row['departmentName'];
            }
        }
        $deptCount = count($departmentId);
        $count = array();

        for ($n = 0; $n < $deptCount; $n++) {
            $sql = "SELECT 
                    d.departmentName, 
                    COUNT(m.id) AS count 
                    FROM manuscript m 
                    LEFT JOIN department d ON m.department = d.id
                    LEFT JOIN campus c ON m.campus = c.id
                    WHERE m.campus = 3 AND m.status = 1 AND m.department = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bind_param('i', $departmentId[$n]);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                array_push($count, $row['count']);
            } else {
                array_push($count, 0);
            }
        }
        $data = array($departmentName, $count);
        return json_encode($data);
    }

    public function checkUserGroup($id)
    {
        $userID = $this->getID($id);

        $sql = "SELECT * FROM groupings WHERE userID = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserByCampus($deptID, $progID, $id)
    {
        $userId = $this->getID($id);
        $groupNumber = $this->getGroupNumberByUserID($userId);
        $usersId = $this->getUserIdByGroupNumber($groupNumber);
        $notIncluded = $this->getUserIdFromGroupings();
        $role = $this->getRole($userId);
        $sql = "SELECT 
                concat_ws(' ', firstName, lastName) AS fullName               
                FROM user_details
                WHERE departmentID = ?
                AND programID = ?
                AND role != '1'";
        if ($role != 1) {
            if ($usersId != 0) {
                if (is_array($usersId)) {
                    $sql .= " AND id IN (" . implode(',', $usersId) . ")";
                } else {
                    $sql .= " AND id = " . $usersId . "";
                }
                $sql .= " AND id NOT IN (" . $userId . ")";
            } else {
                if (is_array($notIncluded)) {
                    if (!in_array($userId, $notIncluded)) {
                        $sql .= " AND id NOT IN (" . implode(',', $notIncluded) . ")";
                    }
                } else {
                    $sql .= " AND id NOT IN (" . $notIncluded . ")";
                }
                $sql .= " AND id != " . $userId . "";
            }
        }

        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('ii', $deptID, $progID);
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
        $sql = "SELECT * FROM websitetraffic WHERE ipAddress = ?";
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
        if (is_int($srCode)) {
            $stmt->bind_param('i', $srCode);
        } else {
            $stmt->bind_param('s', $srCode);
        }
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

        $sql .= " SET u.email = ?, u.firstName = ?, u.middleName = ?, u.lastName = ?, u.campusID = ?, u.departmentID = ?, u.programID = ?";

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
            $stmt->bind_param('ssssiiiss', $data['email'], $data['firstName'], $data['middleName'], $data['lastName'], $data['campus'], $data['department'], $data['program'], $token, $data['srCode']);
        } else {
            $stmt->bind_param('ssssiiis', $data['email'], $data['firstName'], $data['middleName'], $data['lastName'], $data['campus'], $data['department'], $data['program'], $data['srCode']);
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
            $mail->Host       = $this->host;                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = EMAIL;                     //SMTP username
            $mail->Password   = PASSWORD;                               //SMTP password
            $mail->SMTPSecure = "tls";            //Enable implicit TLS encryption
            $mail->Port       = 587;                                  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom(EMAIL, $this->emailName);
            $mail->addAddress($email);     //Add a recipient

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            if ($type == "verify") {
                $mail->Subject = 'Account Verification - BatStateU-Malvar Thesis Repository and Management System';
                $email_header = "<h3>Hi " . "<b>" . $name . "</b>" . ',</h3>';
                $email_text = "<span>This is to inform you that you have successfully changed your email! Please <a href='" . $this->url . "index.php?title=Verify Account&tokenKey=" . $tokenKey . "&srCode=" . $srCode . "'>click here</a> to verify your new email address.</span><br><br>";
                $email_footer = "This is a system generated message. Please do not reply.";
            } else if ($type == "change") {
                $mail->Subject = 'Password Has Been Changed - BatStateU-Malvar Thesis Repository and Management System';
                $email_header = "<h3>Hi " . "<b>" . $name . "</b>" . ',</h3>';
                $email_text = "<span>This is to inform you that you have recently changed your password. If you did not do this change, contact us immediately.</span><br><br>";
                $email_footer = "This is a system generated message. Please do not reply.";
            } else if ($type == "Create Account") {
                $mail->Subject = 'Administrator Account Created - BatStateU-Malvar Thesis Repository and Management System';
                $email_header = "<h3>Hi " . "<b>" . $name . "</b>" . ',</h3>';
                $email_text = "<span>This is to inform you that an administrator account has been created using your email. Please <a href='" . $this->url . "index.php?title=Verify Account&tokenKey=" . $tokenKey . "&srCode=" . $srCode . "'>click here</a> to verify your account</span><br><br>";
                $email_footer = "This is a system generated message. Please do not reply.";
            }

            $email_template = $email_header . $email_text . $email_footer;
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
