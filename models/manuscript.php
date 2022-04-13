<?php

require('../assets/vendor/autoload.php');

class Manuscript extends Database
{
    // MANUSCRIPT TABLE
    public function getManuscriptTable()
    {
        $sql = "SELECT 
                id,
                manuscriptTitle,
                dateUploaded
                FROM manuscript";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $totalData = 0;
            $data = [];
            while ($row = $result->fetch_assoc()) {
                extract($row);
                $totalData++;
                $data[] = [
                    $totalData,
                    $manuscriptTitle,
                    "",
                    $dateUploaded = (new DateTime($dateUploaded))->format('F d, Y - h:i A'),
                    '
                        <button type="button" class="btn btn-warning btn-sm edit" data-id="' . $id . '" data-bs-toggle="modal" data-bs-target="">EDIT</button>
                        <button type="button" class="btn btn-danger btn-sm delete" data-id="' . $id . '">DELETE</button>
                    ',
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
