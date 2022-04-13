<?php

require('../assets/vendor/autoload.php');

class Upload extends Database
{
    //-----------------------Upload File
    public function uploadFiles($values, $files)
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
        
        $insert = $this->insertToDB($values, $file_name_new);
        return $insert;
    }
    public function insertToDB($values, $file_name_new)
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

        $sql = "INSERT INTO manuscript(manuscriptTitle, abstract, journal, yearPub, author, department, campus, dateUploaded, status) VALUES (? , ? , ? , ? , ? , ? , ? , ? , '0')";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('ssssssss', $title, $abstract, $journal, $yearPub, $authors, $department, $program, $dateNow);
        $stmt->execute();
        $stmt->close();
    }
}
