<?php

class Upload extends Database
{
    //-----------------------Upload File
    public function uploadFiles($values, $files, $srCode)
    {
        $type = ['journal', 'abstract'];
        foreach ($type as $key => $value) {
            $path = "../assets/uploads/";
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $file_name = $files[$value]['name'];
            $file_size = $files[$value]['size'];
            $file_tmp = $files[$value]['tmp_name'];
            $file_type = $files[$value]['type'];
            $file_ext = strtolower(end(explode('.', $file_name)));

            $title = $values['title'];
            $title = str_replace(" ", "", $values['title']);

            $file_name_new = $values['yearPub'] . '_' . substr($title, 0, 100);

            $file_path = $path . $file_name_new . '_' . $value . '.' . $file_ext;
            move_uploaded_file($file_tmp, $file_path);
        }

        if ($values['department']) {
            $insert = $this->insertToDBAdmin($values, $file_name_new, $srCode);
        } else {
            $insert = $this->insertToDBUser($values, $file_name_new, $srCode);
        }

        return $insert;
    }
    public function insertToDBAdmin($values, $file_name_new, $srCode)
    {
        // return print_r($values);
        $abstract = $file_name_new . '_abstract.pdf';
        $journal = $file_name_new . '_journal.pdf';
        $title = $values['title'];
        $yearPub = $values['yearPub'];
        $authors = $values['authors'];
        $department = $values['department'];
        $program = $values['program'];
        $dateNow = date("Y-m-d H:i:s");

        $sql = "INSERT INTO manuscript(manuscriptTitle, abstract, journal, yearPub, author, department, campus, dateUploaded, srCode, status) VALUES (? , ? , ? , ? , ? , ? , ? , ? , ? , '1')";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('sssssssss', $title, $abstract, $journal, $yearPub, $authors, $department, $program, $dateNow, $srCode);
        $stmt->execute();
        $stmt->close();
    }

    public function insertToDBUser($values, $file_name_new, $srCode)
    {
        // return print_r($values);
        $abstract = $file_name_new . '_abstract.pdf';
        $journal = $file_name_new . '_journal.pdf';
        $title = $values['title'];
        $yearPub = $values['yearPub'];
        $authors = $values['authors'];

        $dateNow = date("Y-m-d H:i:s");

        $sql = "SELECT *, c.id AS campID, d.id AS deptID FROM user_details u 
                LEFT JOIN campus c ON c.id = u.campusID 
                LEFT JOIN department d ON d.id = u.departmentID
                WHERE u.srCode = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('s', $srCode);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $program = $row['campID'];
                $department = $row['deptID'];
            }
        }

        $sql = "INSERT INTO manuscript(manuscriptTitle, abstract, journal, yearPub, author, department, campus, dateUploaded, srCode, status) VALUES (? , ? , ? , ? , ? , ? , ? , ? , ? , '0')";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('sssssssss', $title, $abstract, $journal, $yearPub, $authors, $department, $program, $dateNow, $srCode);
        $stmt->execute();
        $stmt->close();

        $this->insertNotification($title);
    }

    private function getAdmin()
    {
        $sql = "SELECT id FROM user_details WHERE role = '1'";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $id = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id[] = $row['id'];
            }
        }
        return $id;
    }

    private function insertNotification($title)
    {
        $userID = $this->getAdmin();
        $message = $title . $this->messages[1];
        $dateNow = date("Y-m-d H:i:s");
        $sql = "INSERT INTO notification(userID, type, notifMessage, redirect, dateReceived) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('issss', $userID, $this->typeID[1], $message, $this->redirect[1], $dateNow);
        $stmt->execute();
        $stmt->close();
    }
}
