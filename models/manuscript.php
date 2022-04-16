<?php

class Manuscript extends Database
{
    public function getManuscriptDetails($manuscriptId)
    {
        $sql = "SELECT
                m.id,
                m.manuscriptTitle,
                m.abstract,
                m.journal,
                m.author,
                m.yearPub,
                m.campus,
                m.department,
                d.departmentName
                FROM manuscript m
                lEFT JOIN department d ON m.department = d.id
                WHERE m.id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('i', $manuscriptId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            extract($row);
            $data[] = $row;
        }
        return trim(json_encode($data), '[]');
    }

    // MANUSCRIPT TABLE
    public function getManuscriptTable()
    {
        $sql = "SELECT
                m.id,
                m.manuscriptTitle,
                m.author,
                m.yearPub,
                m.dateUploaded,
                c.campusName,
                d.departmentName
                FROM manuscript m
                LEFT JOIN campus c ON m.campus = c.id
                lEFT JOIN department d ON m.department = d.id
                WHERE m.status = 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $totalData = 0;
        $data = [];
        while ($row = $result->fetch_assoc()) {
            extract($row);
            $totalData++;
            $data[] = [
                $totalData,
                "<a href='#viewJournalModal' class='view-journal' data-id='" . $id . "' data-bs-toggle='modal' data title='Click to view: " . $manuscriptTitle . "'>" . $manuscriptTitle . "</a>",
                $author,
                $yearPub,
                $campusName,
                $departmentName,
                $dateUploaded = (new DateTime($dateUploaded))->format('F d, Y - h:i A'),
                '   <button type="button" class="btn btn-warning btn-sm edit" data-id="' . $id . '" data-bs-toggle="modal" data-bs-target="#editManuscriptModal">EDIT</button>
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

    public function getBrowseManuscriptTable()
    {
        $sql = "SELECT * FROM manuscript WHERE status = 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $totalData = 0;
        $data = [];
        while ($row = $result->fetch_assoc()) {
            extract($row);
            $totalData++;
            $data[] = [
                $totalData,
                "<a href='#viewAbstractModal' id='viewAbstractUser' data-bs-toggle='modal' data-id='" . $id . "' data title='Click to view: " . $manuscriptTitle . "'>" . $manuscriptTitle . "</a>",
                str_replace(";", "<br>", $author),
                $yearPub,
                '<button type="button" class="btn btn-primary btn-sm edit" data-id="' . $id . '" data-bs-toggle="modal" data-bs-target="#">DOWNLOAD</button>
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

    public function getPendingManuscriptTable()
    {
        $sql = "SELECT m.id,
                       m.manuscriptTitle,
                       m.author,
                       m.yearPub,
                       m.dateUploaded,
                       c.campusName,
                       d.departmentName
                       FROM manuscript m
                       LEFT JOIN campus c ON m.campus = c.id
                       lEFT JOIN department d ON m.department = d.id
                       WHERE m.status = 0";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $totalData = 0;
        $data = [];
        while ($row = $result->fetch_assoc()) {
            extract($row);
            $totalData++;
            $data[] = [
                $totalData,
                "<a href='#viewJournalModal' data-bs-toggle='modal' data title='Click to view: " . $manuscriptTitle . "'>" . $manuscriptTitle . "</a>",
                $author,
                $yearPub,
                $campusName,
                $departmentName,
                $dateUploaded = (new DateTime($dateUploaded))->format('F d, Y - h:i A'),
                '   <button type="button" class="btn btn-success btn-sm approved-pending" data-id="' . $id . '">APPROVE</button>
                    <button type="button" class="btn btn-danger btn-sm decline-pending" data-id="' . $id . '">DECLINE</button>
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

    public function getRequestAdminTable()
    {
        $sql = "SELECT 
                id,
                manuscriptTitle,
                dateUploaded
                FROM manuscript WHERE status = 0";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $totalData = 0;
        $data = [];
        while ($row = $result->fetch_assoc()) {
            extract($row);
            $totalData++;
            $data[] = [
                $totalData,
                $dateUploaded = (new DateTime($dateUploaded))->format('F d, Y - h:i A'),
                $manuscriptTitle,
                "",
                "",
                '
                    <button type="button" class="btn btn-success btn-sm approve-request" data-id="' . $id . '" data-bs-toggle="modal" data-bs-target="">APPROVE</button>
                    <button type="button" class="btn btn-danger btn-sm disapprove-request" data-id="' . $id . '">DISAPPROVE</button>
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

    public function deleteManuscript($manuscriptId)
    {
        $sql = "DELETE FROM manuscript WHERE id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("i", $manuscriptId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function updateManuscript($data)
    {
        extract($data);
        $sql = "UPDATE manuscript SET manuscriptTitle = ?, author = ?, yearPub = ?, campus = ?, department = ? WHERE id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("ssiiii", $manuscriptTitle, $manuscriptAuthors, $manuscriptYearPub, $manuscriptCampus, $manuscriptDept, $manuscriptId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function approveManuscript($manuscriptId)
    {
        $sql = "UPDATE manuscript SET status = 1 WHERE id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("i", $manuscriptId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return 1;
            $this->insertNotification($manuscriptId, "Approved");
        } else {
            return 0;
        }
    }

    public function declineManuscript($manuscriptId)
    {
        $sql = "UPDATE manuscript SET status = 2 WHERE id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("i", $manuscriptId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return 1;
            $this->insertNotification($manuscriptId, "Declined");
        } else {
            return 0;
        }
    }

    private function insertNotification($manuscriptId, $status)
    {
        $srCode = $this->getSRCode("manuscript", "srCode", "id", $manuscriptId);
        $title = $this->getManuscriptTitle($manuscriptId);
        $id = $this->getID($srCode);
        $type = ($status == "Approved") ? $this->typeID[2] : $this->typeID[3];
        $message = ($status == "Approved") ? $this->messages[2] : $this->messages[3] . " Insert Reason Here";

        $sql = "INSERT INTO notification (userID, type, notifMessage, redirect, dateReceived)"
            . " VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("issss", $id, $type, $title . " $message", $this->redirect[2], $this->dateNow());
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function getManuscriptTitle($manuscriptID)
    {
        $sql = "SELECT manuscriptTitle FROM manuscript WHERE id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("i", $manuscriptID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['manuscriptTitle'];
    }
}
