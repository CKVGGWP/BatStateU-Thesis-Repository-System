<?php

require('../assets/vendor/autoload.php');

class Accounts extends Database
{
    public function getAccountsTable()
    {
        $sql = "SELECT 
                u.srCode, 
                u.email, 
                CONCAT(u.firstName, ' ' , u.middleName , ' ' , u.lastName) AS name, 
                u.role, 
                d.departmentName, 
                c.campusName
                FROM user_details u 
                LEFT JOIN department d ON u.departmentID = d.id 
                LEFT JOIN campus c ON d.campusID = c.id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $totalData = 0;
            while ($row = $result->fetch_assoc()) {
                extract($row);
                $totalData++;
                $data[] = [
                    $totalData,
                    $srCode,
                    $name,
                    $campusName,
                    $departmentName,
                    $email,
                    $role == 1 ? 'Admin' : 'User',
                    '<div class="btn-group-vertical">
                        <button type="button" class="btn btn-warning btn-sm mb-2" data-srCode="'.$srCode.'" data-bs-toggle="modal" data-bs-target="#editAccounts">UPDATE</button>
                        <button type="button" class="btn btn-danger btn-sm delete" data-srCode="'.$srCode.'">DELETE</button>
                    </div>'

                ];
            }

            $json_data = array(
                "draw"            => 1,   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
                "recordsTotal"    => intval($totalData),  // total number of records
                "recordsFiltered" => intval($totalData), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data"            => $data   // total data array

            );

            return json_encode($json_data);  // send data as json format
        }
    }
}
