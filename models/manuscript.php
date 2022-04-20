<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
    public function getManuscriptTable($recent = '')
    {
        $sql = "SELECT
                m.id,
                m.manuscriptTitle,
                m.author,
                m.yearPub,
                m.dateUploaded,
                m.actionDate,
                c.campusName,
                d.departmentName,
                m.tags
                FROM manuscript m
                LEFT JOIN campus c ON m.campus = c.id
                lEFT JOIN department d ON m.department = d.id
                WHERE m.status = 1";

        if ($recent != '') {
            $sql .= " AND actionDate >= NOW() - INTERVAL 7 DAY";
        }
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $totalData = 0;
        $now = date('Y-m-d');
        $data = [];
        while ($row = $result->fetch_assoc()) {
            extract($row);
            $totalData++;
            if ($recent != '') {
                $data[] = [
                    $totalData,
                    "<a href='#viewJournalModal' class='view-journal' data-id='" . $id . "' data-bs-toggle='modal' data title='Click to view: " . $manuscriptTitle . "'>" . $manuscriptTitle . "</a>",
                    str_replace(",", "<br>", $author),
                    $actionDate = ((strtotime($now) - strtotime(date('Y-m-d', strtotime($actionDate)))) / 60 / 60 / 24) == 0 ? 'Today' : (((strtotime($now) - strtotime(date('Y-m-d', strtotime($actionDate)))) / 60 / 60 / 24) == 1 ? 'Yesterday' : ((strtotime($now) - strtotime(date('Y-m-d', strtotime($actionDate)))) / 60 / 60 / 24) . ' days ago'),


                ];
            } else {
                $data[] = [
                    $totalData,
                    "<a href='#viewJournalModal' class='view-journal' data-id='" . $id . "' data-bs-toggle='modal' data title='Click to view: " . $manuscriptTitle . "'>" . $manuscriptTitle . "</a>",
                    str_replace(",", "<br>", $author) ?? $author,
                    $yearPub,
                    $campusName,
                    $departmentName,
                    $dateUploaded = (new DateTime($dateUploaded))->format('F d, Y - h:i A'),
                    '   <button type="button" class="btn btn-warning btn-sm edit" data-id="' . $id . '" data-bs-toggle="modal" data-bs-target="#editManuscriptModal">EDIT</button>
                        <button type="button" class="btn btn-danger btn-sm delete" data-id="' . $id . '">DELETE</button>
                    ',
                    $tags,
                ];
            }


            $authors = explode(',', $author);
            $firstName = explode(' ', $authors[0]);
            $firstName = $firstName[0];
        }

        $json_data = array(
            "draw"            => 1,   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal"    => intval($totalData),  // total number of records
            "recordsFiltered" => intval($totalData), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data"            => $data   // total data array

        );

        return json_encode($json_data);  // send data as json format
    }


    public function getBrowseManuscriptTable($srCode)
    {
        $userId = $this->getID($srCode);
        $groupNumber = $this->getGroupNumberByUserID($userId);
        $groupId = $this->getIdByGroupNumber($groupNumber);
        $totalData = 0;
        $data = [];

        //OWN MANUSCRIPTS
        $sql = "SELECT *, m.id AS excludeID 
        FROM manuscript m 
        JOIN groupings g ON g.manuscriptID = m.id 
        WHERE status = 1 AND g.groupNumber = ? AND
        NOT EXISTS 
            (SELECT * FROM manuscript_token 
             WHERE m.id = manuscript_token.manuscriptID 
             AND manuscript_token.status = '1' 
             AND manuscript_token.isValid ='0' 
        
             ) 
             GROUP BY m.id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('i', $groupId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            extract($row);
            $excludeID;
            $totalData++;
            $data[] = [
                $totalData,
                "<a href='#viewAbstractModal' id='viewAbstractUser' data-bs-toggle='modal' data-id='" . $id . "' data title='Click to view: " . $manuscriptTitle . "'>" . $manuscriptTitle . "</a>",
                str_replace(",", "<br>", $author) ?? $author,
                $yearPub,
                '<a href="' . $this->url . "assets/uploads/" . $journal . '" class="btn btn-success btn-sm" data-id="' . $id . '" download>DOWNLOAD</a>
                 ',
                $tags,

            ];
        }

        //Transfer ID to Exclude Own ID
        $excludedManuscriptId = isset($excludeID) ? $excludeID : 0;

        //EXPIRED MANUSCRIPTS
        $sql = "SELECT * FROM manuscript WHERE status = 1 AND (NOT id=? ) AND EXISTS (SELECT * FROM manuscript_token WHERE manuscript.id = manuscript_token.manuscriptID AND manuscript_token.status = '3' AND manuscript_token.isValid ='0' AND manuscript_token.groupID = ?);";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('ii', $excludedManuscriptId, $groupId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            extract($row);
            $totalData++;
            $data[] = [
                $totalData,
                "<a href='#viewAbstractModal' id='viewAbstractUser' data-bs-toggle='modal' data-id='" . $id . "' data title='Click to view: " . $manuscriptTitle . "'>" . $manuscriptTitle . "</a>",
                str_replace(",", "<br>", $author) ?? $author,
                $yearPub,
                '<div class="btn-group-vertical">
                <button disabled type="button" class="btn btn-dark btn-sm edit" data-id="' . $id . '" data-bs-toggle="modal" data-bs-target="#">EXPIRED</button>                
                <button type="button" class="btn btn-danger btn-sm edit request" data-id="' . $id . '" data-bs-toggle="modal" data-bs-target="#">REQUEST</button>
                </div>',
                $tags,

            ];
        }

        //REQUESTED MANUSCRIPT
        $sql = "SELECT * FROM manuscript WHERE status = 1 AND (NOT id=? ) AND EXISTS (SELECT * FROM manuscript_token WHERE manuscript.id = manuscript_token.manuscriptID AND manuscript_token.status = '1' AND manuscript_token.isValid ='0' AND manuscript_token.groupID = ?);";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('ii', $excludedManuscriptId, $groupId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            extract($row);
            $totalData++;
            $data[] = [
                $totalData,
                "<a href='#viewAbstractModal' id='viewAbstractUser' data-bs-toggle='modal' data-id='" . $id . "' data title='Click to view: " . $manuscriptTitle . "'>" . $manuscriptTitle . "</a>",
                str_replace(",", "<br>", $author) ?? $author,
                $yearPub,
                '<button type="button" class="btn btn-success btn-sm edit download" data-id="' . $id . '" data-bs-toggle="modal" data-bs-target="#passwordModal">DOWNLOAD</button>
                ',
                $tags,
            ];
        }

        //PENDING MANUSCRIPTS
        $sql = "SELECT * FROM manuscript WHERE status = 1 AND (NOT id=? ) AND EXISTS (SELECT * FROM manuscript_token WHERE manuscript.id = manuscript_token.manuscriptID AND manuscript_token.status = '0' AND manuscript_token.isValid ='0' AND manuscript_token.groupID = ?);";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('ii', $excludedManuscriptId, $groupId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            extract($row);
            $totalData++;
            $data[] = [
                $totalData,
                "<a href='#viewAbstractModal' id='viewAbstractUser' data-bs-toggle='modal' data-id='" . $id . "' data title='Click to view: " . $manuscriptTitle . "'>" . $manuscriptTitle . "</a>",
                str_replace(",", "<br>", $author) ?? $author,
                $yearPub,
                '<button disabled type="button" class="btn btn-warning btn-sm edit PENDING" data-id="' . $id . '" data-bs-toggle="modal" data-bs-target="#">PENDING</button>
                ',
                $tags,

            ];
        }

        //DECLINED MANUSCRIPTS
        $sql = "SELECT * FROM manuscript WHERE status = 1 AND (NOT id=? ) AND EXISTS (SELECT * FROM manuscript_token WHERE manuscript.id = manuscript_token.manuscriptID AND manuscript_token.status = '2' AND manuscript_token.isValid ='0' AND manuscript_token.groupID = ?);";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('ii', $excludedManuscriptId, $groupId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            extract($row);
            $totalData++;
            $data[] = [
                $totalData,
                "<a href='#viewAbstractModal' id='viewAbstractUser' data-bs-toggle='modal' data-id='" . $id . "' data title='Click to view: " . $manuscriptTitle . "'>" . $manuscriptTitle . "</a>",
                str_replace(",", "<br>", $author) ?? $author,
                $yearPub,
                '<div class="btn-group-vertical">
                <button disabled type="button" class="btn btn-dark btn-sm edit" data-id="' . $id . '" data-bs-toggle="modal" data-bs-target="#">DECLINED</button>
                
                </div>',
                $tags,
            ];
        }

        //NOT REQUESTED MANUSCRIPTS
        $sql = "SELECT * FROM manuscript WHERE status = 1 AND NOT id=?  AND NOT EXISTS (SELECT * FROM manuscript_token WHERE manuscript.id = manuscript_token.manuscriptID AND manuscript_token.isValid ='0' AND manuscript_token.groupID = ?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('ii', $excludedManuscriptId, $groupId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            extract($row);
            $totalData++;
            $data[] = [
                $totalData,
                "<a href='#viewAbstractModal' id='viewAbstractUser' data-bs-toggle='modal' data-id='" . $id . "' data title='Click to view: " . $manuscriptTitle . "'>" . $manuscriptTitle . "</a>",
                str_replace(",", "<br>", $author) ?? $author,
                $yearPub,
                '<button type="button" class="btn btn-danger btn-sm edit request" data-id="' . $id . '" data-bs-toggle="modal" data-bs-target="#">REQUEST</button>
                ',
                $tags,
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
                "<a href='#viewJournalModal' class='view-journal' data-bs-toggle='modal' data-id = '" . $id . "' data title='Click to view: " . $manuscriptTitle . "'>" . $manuscriptTitle . "</a>",
                str_replace(",", "<br>", $author) ?? $author,
                $yearPub,
                $campusName,
                $departmentName,
                $dateUploaded = (new DateTime($dateUploaded))->format('F d, Y - h:i A'),
                '   <button type="button" class="btn btn-success btn-sm approved-pending" data-id="' . $id . '">APPROVE</button>
                    <button type="button" data-bs-target="#reasonModal" data-bs-toggle="modal" class="btn btn-danger btn-sm">DECLINE</button>
                    <input type="hidden" class="manuscriptBox" value="' . $id . '"></input>
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

    public function getRequestAdminTable($srCode = '')
    {
        $sql = "SELECT
                DISTINCT
                t.id,
                t.manuscriptID,
                t.userID,
                t.dateRequested,
                t.time,
                t.status,
                m.manuscriptTitle,
                m.author,
                t.status,
                g.reason,
                CONCAT(u.firstName, ' ' , u.middleName , ' ' , u.lastName) AS name
                FROM manuscript_token t
                LEFT JOIN manuscript m ON t.manuscriptID = m.id
                LEFT JOIN user_details u ON t.userID = u.id
                LEFT JOIN groupings g ON t.manuscriptID = g.manuscriptID";

        if ($srCode == '') {
            $sql .= " WHERE t.status = 0";
            $stmt = $this->connect()->prepare($sql);
        } else {
            $sql .= " WHERE u.srCode = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bind_param("s", $srCode);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $totalData = 0;
        $data = [];
        while ($row = $result->fetch_assoc()) {
            extract($row);
            $totalData++;

            if ($srCode == '') {
                $data[] = [
                    $totalData,
                    $dateRequested = (new DateTime($dateRequested))->format('F d, Y - h:i A'),
                    $manuscriptTitle,
                    str_replace(",", "<br>", $author) ?? $author,
                    $name,
                    '
                        <button type="button" class="btn btn-success btn-sm approve-request" data-id="' . $id . '" data-bs-toggle="modal" data-bs-target="">APPROVE</button>
                        <button type="button" data-bs-target="#reasonRequestModal" data-bs-toggle="modal" class="btn btn-danger btn-sm">DECLINE</button>
                        <input type="hidden" class="requestBox" value="' . $id . '"></input>
                    ',
                ];
            } else {
                $data[] = [
                    $manuscriptTitle,
                    str_replace(",", "<br>", $author) ?? $author,
                    $status = ($status == 0) ? '<span class="badge bg-warning">PENDING</span>' : (($status == 1) ? '<span class="badge bg-success">APPROVED</span>' : '<span class="badge bg-danger">DECLINED</span>'),
                    $reason,
                ];
            }
        }

        $json_data = array(
            "draw"            => 1,   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal"    => intval($totalData),  // total number of records
            "recordsFiltered" => intval($totalData), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data"            => $data   // total data array

        );

        return json_encode($json_data);  // send data as json format
    }

    public function getRequestHistoryTable()
    {
        $sql = "SELECT
                DISTINCT 
                t.id,
                t.manuscriptID,
                t.userID,
                t.time,
                t.dateRequested,
                t.status,
                m.manuscriptTitle,
                m.author,
                CONCAT(u.firstName, ' ' , u.middleName , ' ' , u.lastName) AS name
                FROM manuscript_token t
                LEFT JOIN manuscript m ON t.manuscriptID = m.id
                LEFT JOIN user_details u ON t.userID = u.id
                WHERE t.status != 0";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $totalData = 0;
        $data = [];
        while ($row = $result->fetch_assoc()) {
            extract($row);
            $totalData++;
            if ($status == 1) {
                $buttonValue = '<button type="button" disabled class="btn btn-success btn-sm">APPROVED</button>';
            } else {
                $buttonValue = '<button type="button" disabled class="btn btn-danger btn-sm">DECLINED</button>';
            }
            $data[] = [
                $totalData,
                $dateRequested = (new DateTime($dateRequested))->format('F d, Y - h:i A'),
                $manuscriptTitle,
                str_replace(",", "<br>", $author) ?? $author,
                $name,
                $buttonValue,
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

    public function getManuscriptBySrCode($srCode)
    {
        $sql = "SELECT
                m.id,
                g.reason,
                m.manuscriptTitle,
                m.status,
                m.dateUploaded
                FROM groupings g 
                LEFT JOIN user_details u ON g.userID = u.id
                LEFT JOIN manuscript m ON g.manuscriptID = m.id
                WHERE u.srCode = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("s", $srCode);
        $stmt->execute();
        $result = $stmt->get_result();

        $totalData = 0;
        $data = [];
        while ($row = $result->fetch_assoc()) {
            extract($row);
            $totalData++;
            $data[] = [
                "<a href='#viewJournalModal' class='view-journal' data-bs-toggle='modal' data-id='" . $id . "' data title='Click to view: " . $manuscriptTitle . "'>" . $manuscriptTitle . "</a>",
                $dateUploaded = (new DateTime($dateUploaded))->format('F d, Y - h:i A'),
                $status = ($status == 0) ? '<span class="badge bg-warning">PENDING</span>' : (($status == 1) ? '<span class="badge bg-success">APPROVED</span>' : '<span class="badge bg-danger">DECLINED</span>'),
                $reason
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

    public function getGroupNumber($srCode)
    {
        $sql = "SELECT
                g.groupNumber
                FROM groupings g 
                LEFT JOIN user_details u ON g.userID = u.id
                WHERE u.srCode = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("s", $srCode);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        extract($row);
        $pending = $this->getPendingManuscriptByGroup($groupNumber);
        $apprrove = $this->getApproveManuscriptByGroup($groupNumber);

        if ($pending > 0) {
            $data = [
                "pending" => $pending,
                "approve" => false,
                "message" => "You have pending manuscript!"
            ];
        } else if ($apprrove > 0) {
            $data = [
                "pending" => false,
                "approve" => $apprrove,
                "message" => "Your manuscript is already approved!"
            ];
        } else {
            $data = [
                "pending" => false,
                "approve" => false,
                "message" => "Please upload your manuscript."
            ];
        }
        return json_encode($data);
    }

    public function getPendingManuscriptByGroup($groupNumber)
    {
        $sql = "SELECT 
                m.id
                FROM groupings g 
                LEFT JOIN manuscript m ON g.manuscriptID = m.id
                WHERE m.status = 0 AND g.groupNumber = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("i", $groupNumber);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows;
    }

    public function getApproveManuscriptByGroup($groupNumber)
    {
        $sql = "SELECT 
                m.id
                FROM groupings g 
                LEFT JOIN manuscript m ON g.manuscriptID = m.id
                WHERE m.status = 1 AND g.groupNumber = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("i", $groupNumber);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows;
    }


    public function deleteManuscript($manuscriptId)
    {
        $this->deleteFiles($this->getManuscriptFiles($manuscriptId));
        $sql = "DELETE FROM manuscript WHERE id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("i", $manuscriptId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $this->deleteManuscriptToken($manuscriptId);
            $this->deleteManuscriptFromGroupings($manuscriptId);
            return 1;
        } else {
            return 0;
        }
    }

    private function deleteManuscriptToken($manuscriptId)
    {
        $sql = "DELETE FROM manuscript_token WHERE manuscriptID = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("i", $manuscriptId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    private function deleteManuscriptFromGroupings($manuscriptId)
    {
        $sql = "DELETE FROM groupings WHERE manuscriptID = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("i", $manuscriptId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    private function updateCurrentRequest($id, $groupNumber, $manuscriptID)
    {
        $sql = "UPDATE manuscript_token SET status = 0 WHERE manuscriptID = ? AND (groupID = ? OR userID = ?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("iii", $id, $groupNumber, $manuscriptID);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return $this->insertNotification($manuscriptID, "Pending", "request");
        } else {
            $data = [
                "success" => false,
                "message" => "Error inserting",
                "error" => "Something went wrong! Error: " . $stmt->error
            ];
            return json_encode($data);
        }
    }

    private function selectCurrentRequest($id, $groupNumber, $manuscriptID)
    {
        $sql = "SELECT * FROM manuscript_token WHERE manuscriptID = ? AND (status = 1 OR status = 3) AND (groupID = ? OR userID = ?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("iii", $id, $groupNumber, $manuscriptID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function requestManuscript($srCode, $manuscriptId)
    {
        $token = $this->createOTP();
        $id = $this->getID($srCode);
        $groupNumber = $this->getGroupNumberByUserID($id);

        if ($this->selectCurrentRequest($id, $groupNumber, $manuscriptId)) {
            return $this->updateCurrentRequest($id, $groupNumber, $manuscriptId);
            exit();
        }

        if ($groupNumber != 0) {
            $sql = "INSERT INTO manuscript_token(id, manuscriptID, groupID, status, token, dateRequested, dateApproved, time) VALUES (NULL, ?, ?, 0, ?, NOW(), 0, 0)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bind_param("iis", $manuscriptId, $groupNumber, $token);
        } else {
            $sql = "INSERT INTO manuscript_token(id, manuscriptID, userID, status, token, dateRequested, dateApproved, time) VALUES (NULL, ?, ?, 0, ?, NOW(), 0, 0)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bind_param("iis", $manuscriptId, $id, $token);
        }

        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return $this->insertNotification($manuscriptId, "Pending", "request");
        } else {
            $data = [
                "success" => false,
                "message" => "Error inserting",
                "error" => "Something went wrong! Error: " . $stmt->error
            ];
            return json_encode($data);
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
            $data = [
                "success" => true,
                "message" => $manuscriptTitle . " updated successfully"
            ];
        } else {
            $data = [
                "success" => false,
                "message" => "Error updating " . $manuscriptTitle,
                "error" => "Something went wrong! Error: " . $stmt->error
            ];
        }
        return json_encode($data);
    }

    public function updatePendingManuscript($manuscriptId, $status, $date, $reason)
    {
        $sql = "UPDATE manuscript SET status = ?, actionDate = ? WHERE id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("isi", $status, $date, $manuscriptId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return $this->insertNotification($manuscriptId,  $status = ($status == 1) ? "Approved" : "Declined", "", $reason);
        } else {
            return 0;
        }
    }

    private function insertNotification($manuscriptId, $status = "", $request = "", $reason = "", $id = "")
    {
        $title = $this->getManuscriptTitle($manuscriptId);

        if ($status == "Pending") {
            $id = $this->getSRCode("user_details", "id", "role", "1");
        } else {
            $id = $this->getSRCodeByNames($this->getAuthorByID($manuscriptId));
        }

        if ($request == "") {
            $type = ($status == "Approved") ? $this->typeID[2] : $this->typeID[3];
        } else {
            $type = ($status == "Pending") ? $this->typeID[4] : (($status == "Approved") ? $this->typeID[5] : $this->typeID[6]);
        }


        $message = $title;

        if ($request == "") {
            $message .= ($status == "Approved") ? $this->messages[2] : $this->messages[3] . $reason;
        } else {
            $message .= ($status == "Pending") ? $this->messages[4] : (($status == "Approved") ? $this->messages[5] : $this->messages[6] . $reason);
        }

        if ($request == "") {
            $redirect = $this->redirect[2];
        } else {
            $redirect = $this->redirect[1];
        }

        $dateNow = dateTimeNow();

        foreach ($id as $key => $value) {
            $sql = "INSERT INTO notification (userID, type, notifMessage, redirect, dateReceived)"
                . " VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bind_param("issss", $value, $type, $message, $redirect, $dateNow);
            $stmt->execute();
        }

        if ($stmt->affected_rows > 0) {
            $data = array(
                "success" => true,
                "title" => $title,
            );
        } else {
            $data = array(
                "success" => false,
                "title" => $title,
                "error" => mysqli_errno($this->connect()),
            );
        }

        $this->addReason($manuscriptId, $reason);

        return json_encode($data);
    }

    private function insertRequestNotification($manuscriptData, $status, $reason)
    {
        $title = $this->getManuscriptTitle($manuscriptData[0]['manuscriptID']);

        if ($manuscriptData[0]['groupID'] == 0) {
            $id = $manuscriptData[0]['userID'];
        } else {
            $id = $this->getIdByGroupNumber($manuscriptData[0]['groupID']);
        }

        $token = $manuscriptData[0]['token'];

        $type = ($status == "Approved") ? $this->typeID[5] : $this->typeID[6];

        $message = $title;

        $message .= ($status == "Approved") ? $this->messages[5] : $this->messages[6] . $reason;

        $redirect = $this->redirect[4] . "&password=" . $token . "";

        $dateNow = dateTimeNow();

        $details = $this->getUserDetails($id);

        $this->updateTime($token);

        if (is_array($id)) {
            foreach ($id as $key => $value) {
                $sql = "INSERT INTO notification (userID, type, notifMessage, redirect, dateReceived)"
                    . " VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->connect()->prepare($sql);
                $stmt->bind_param("issss", $value, $type, $message, $redirect, $dateNow);
                $stmt->execute();
            }
        } else {
            $sql = "INSERT INTO notification (userID, type, notifMessage, redirect, dateReceived)"
                . " VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bind_param("issss", $id, $type, $message, $redirect, $dateNow);
            $stmt->execute();
        }

        if ($stmt->affected_rows > 0) {
            return $this->sendEmail($details, $title, $token);
        } else {
            $data = array(
                "success" => false,
                "title" => $title,
                "error" => mysqli_errno($this->connect()),
            );
            return json_encode($data);
        }
    }

    private function updateTime($token)
    {
        $sql = "UPDATE manuscript_token SET time = 300 WHERE token = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("s", $token);
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

    public function updateManuscriptRequest($id, $status, $reason)
    {
        $sql = "UPDATE manuscript_token SET status = ?, dateApproved = now() WHERE id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("ii", $status, $id);
        $stmt->execute();

        $manuscriptData = $this->getManuscriptTokenData($id);

        if ($stmt->affected_rows > 0) {
            return $this->insertRequestNotification($manuscriptData, $status = ($status == 1) ? "Approved" : "Declined", $reason);
        } else {
            return 0;
        }
    }

    public function getManuscriptButton()
    {
        $button = '<h5 class="card-title">Manuscript</h5>';
        $sql = "SELECT COUNT(id) AS nums FROM manuscript WHERE status = 0";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['nums'] != 0) {
                $button .= '<a href="dashboard.php?title=Pending Manuscripts" class="btn btn-dark btn-sm my-3">Pending Manuscript <span class="badge bg-primary badge-number">' . $row['nums'] . '</span></a>';
            } else {
                $button .= '<a href="dashboard.php?title=Pending Manuscripts" class="btn btn-dark btn-sm my-3">Pending Manuscript</a>';
            }
        }

        return json_encode($button);
    }

    public function checkPassword($password, $id)
    {
        $userID = $this->getID($id);
        $groupNumber = $this->getGroupNumberByUserID($userID);
        $nums = 0;

        $sql = "SELECT manuscriptID, time FROM manuscript_token WHERE token = ? AND isValid = '0'";

        if ($groupNumber != 0) {
            $sql .= " AND groupID = ?";
            $nums .= $groupNumber;
        } else {
            $sql .= " AND userID = ?";
            $nums .= $userID;
        }

        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("si", $password, $nums);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $this->updateToken($row['manuscriptID'], $password, $nums);
        } else {
            return 0;
        }
    }

    public function updateTokenValidity($srCode)
    {
        $id = $this->getID($srCode);
        $groupNumber = $this->getGroupNumberByUserID($id);
        $nums = 0;

        $sql = "SELECT token FROM manuscript_token WHERE isValid = '0' AND time > 0";

        if ($groupNumber != 0) {
            $sql .= " AND groupID = ?";
            $nums .= $groupNumber;
        } else {
            $sql .= " AND userID = ?";
            $nums .= $id;
        }

        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("i", $nums);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($stmt->affected_rows > 0) {
            return $this->checkSessionTime($row['token'], $nums);
        } else {
            return "Hello";
        }
    }

    private function checkSessionTime($password, $id)
    {
        $date = time() - $_SESSION['time'];
        if ($date > 300) {
            $sql = "UPDATE manuscript_token SET isValid = '1', status = '3' WHERE token = ? AND (userID = ? OR groupID = ?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bind_param("sii", $password, $id, $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                return "Expired";
            } else {
                return "Hi";
            }
        } else {
            $_SESSION['time'] = time();
            return "Not yet!";
        }
    }

    private function updateToken($manuscriptID, $password, $id)
    {
        $sql = "UPDATE manuscript_token SET isValid = '1' WHERE token = ? AND (userID = ? OR groupID = ?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("sii", $password, $id, $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return $this->getManuscript($manuscriptID);
        } else {
            return 0;
        }
    }

    private function getManuscript($id)
    {
        $sql = "SELECT journal FROM manuscript WHERE id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        $row = $result->fetch_assoc();

        if (file_exists($this->directory . $row['journal'])) {
            return $this->url . "assets/uploads/" . $row['journal'];
        } else {
            return 0;
        }
    }

    private function getManuscriptTokenData($id)
    {
        $sql = "SELECT 
                groupID,
                userID,
                manuscriptID,
                token
                FROM manuscript_token
                WHERE id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $data = [];
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    private function getManuscriptFiles($id)
    {
        $sql = "SELECT abstract, journal FROM manuscript WHERE id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    private function addReason($id, $reason)
    {
        $sql = "UPDATE groupings SET reason = ? WHERE manuscriptID = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("si", $reason, $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function sendEmail($details, $title, $token)
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
            $mail->addAddress($details[0]['email']);     //Add a recipient

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Manuscript Request - BatStateU-Malvar Thesis Repository and Management System';
            $email_header = "<h3>Hi " . "<b>" . $details[0]['name'] . "</b>" . ',</h3>';
            $email_text = "<span>This is to inform you that your request for the manuscript titled " . $title . " has been approved!</span><br><br>";
            $email_text2 = "<span>Your password is " . $token . " .This is only available for 5 minutes. <a href='" . $this->url . "dashboard.php?title=Browse Manuscript&password=" . $token . "'>Click here</a> to view your requested manuscript.</span><br><br>";
            $email_footer = "This is a system generated message. Please do not reply.";
            $email_template = $email_header . $email_text . $email_text2 .  $email_footer;
            $mail->Body = $email_template;

            if ($mail->send()) {
                $data = array(
                    "success" => true,
                    "title" => $title,
                );
            } else {
                $data = array(
                    "success" => false,
                    "error" => $mail->ErrorInfo,
                );
            }
        } catch (Exception $e) {
            $data = array(
                "success" => false,
                "error" => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"
            );
        }

        return json_encode($data);
    }
}
