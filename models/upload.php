<?php

class Upload extends Database
{
    //-----------------------Upload File
    public function uploadFiles($values, $files, $srCode)
    {
        $type = ['journal', 'abstract'];
        foreach ($type as $key => $value) {
            $path = $this->directory;
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
        $department = isset($values['department']) ? $values['department'] : 0;
        $program = isset($values['program']) ? $values['program'] : 0;
        $tags = $values['tags'];
        $dateNow = dateTimeNow();

        $sql = "INSERT INTO manuscript(manuscriptTitle, abstract, journal, yearPub, author, campus, department, program, dateUploaded, actionDate, srCode, tags, status) VALUES (? , ? , ? , ? , ? , '3', ? , ? , ? , ? , ? , ? , '1')";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('sssssssssss', $title, $abstract, $journal, $yearPub, $authors, $department, $program, $dateNow, $dateNow, $srCode, $tags);
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
        $tags = $values['tags'];
        $status = 0;

        $dateNow = date("Y-m-d H:i:s");

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
        $stmt->bind_param('s', $srCode);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $program = $row['programID'];
                $department = $row['deptID'];
                $campus = $row['campID'];
            }
        }

        $sql = "INSERT INTO manuscript(manuscriptTitle, abstract, journal, yearPub, author, department, campus, program, dateUploaded, srCode, tags, status) VALUES (? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , '0')";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('sssssssssss', $title, $abstract, $journal, $yearPub, $authors, $department, $campus, $program, $dateNow, $srCode, $tags);
        $stmt->execute();
        $stmt->close();

        if ($this->insertGroup($this->lastManuscriptID())) {
            return $this->insertNotification($title);
        } else {
            return false;
        }
        // return print_r($values);
    }

    private function insertGroup($lastID)
    {
        // if ($this->checkGroup($lastID) == true) {
        //     return true;
        //     exit();
        // }

        // $groupNumber = $this->createGroupNumber();
        $id = $this->getSRCodeByNames($this->getAuthorByID($lastID));

        if(is_array($id)) {
            $groupNumber = $this->checkGroup($id, true);
        } else {
            $groupNumber = $this->checkGroup($id);
        }

        foreach ($id as $key => $value) {
            $sql = "INSERT INTO groupings(userID, groupNumber, manuscriptID) VALUES (? , ? , ?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bind_param('iii', $value, $groupNumber, $lastID);
            $stmt->execute();
        }

        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function checkGroup($id, $array = false)
    {
        $sql = "SELECT groupNumber FROM groupings";

        if($array) {
            $sql .= " WHERE userID IN (" . implode(',', $id) . ")";
            $stmt = $this->connect()->prepare($sql);
        } else {
            $sql .= " WHERE userID = ? LIMIT 1";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bind_param('i', $id);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['groupNumber'];
        } else {
            return $this->createGroupNumber();
        }
    }

    private function createGroupNumber()
    {
        $sql = "SELECT * FROM groupings ORDER BY groupNumber DESC LIMIT 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $groupNumber = $row['groupNumber'] + 1;
        } else {
            $groupNumber = 1;
        }

        return $groupNumber;
    }

    private function getAdmin()
    {
        $sql = "SELECT id FROM user_details WHERE role = '1'";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $id = [];

        while ($row = $result->fetch_assoc()) {
            $id[] = $row['id'];
        }

        return $id;
    }

    private function insertNotification($title)
    {
        $userID = $this->getAdmin();
        $message = $title . $this->messages[1];
        $dateNow = date("Y-m-d H:i:s");
        foreach ($userID as $key => $value) {
            $sql = "INSERT INTO notification(userID, type, notifMessage, redirect, dateReceived) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bind_param('issss', $value, $this->typeID[1], $message, $this->redirect[3], $dateNow);
            $stmt->execute();
        }
        $stmt->close();
    }
}
