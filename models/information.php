<?php

class Information extends Database
{
    public function getCampuses()
    {
        $sql = "SELECT * FROM campus ORDER BY campusName ASC";
        $result = $this->connect()->query($sql);
        $campuses = [];
        while ($row = $result->fetch_assoc()) {
            $campuses[] = $row;
        }
        return $campuses;
    }
}
