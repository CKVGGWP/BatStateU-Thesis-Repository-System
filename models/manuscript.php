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
                d.departmentName
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
        //REQUESTED MANUSCRIPT
        $sql = "SELECT * FROM manuscript WHERE status = 1 AND EXISTS (SELECT * FROM manuscript_token WHERE manuscript.id = manuscript_token.manuscriptID AND manuscript_token.status = '1' AND manuscript_token.userID = ?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('s', $srCode);
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
                str_replace(",", "<br>", $author) ?? $author,
                $yearPub,
                '<button type="button" class="btn btn-primary btn-sm edit download" data-id="' . $id . '" data-bs-toggle="modal" data-bs-target="#">DOWNlOAD</button>
                ',
            ];
        }

        //PENDING MANUSCRIPTS
        $sql = "SELECT * FROM manuscript WHERE status = 1 AND EXISTS (SELECT * FROM manuscript_token WHERE manuscript.id = manuscript_token.manuscriptID AND manuscript_token.status = '0' AND manuscript_token.userID = ?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('s', $srCode);
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
            ];
        }

        //DECLINED MANUSCRIPTS
        $sql = "SELECT * FROM manuscript WHERE status = 1 AND EXISTS (SELECT * FROM manuscript_token WHERE manuscript.id = manuscript_token.manuscriptID AND manuscript_token.status = '2' AND manuscript_token.userID = ?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('s', $srCode);
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
                <button type="button" class="btn btn-danger btn-sm edit request" data-id="' . $id . '" data-bs-toggle="modal" data-bs-target="#">REQUEST</button>
                </div>',
            ];
        }

        //NOT REQUESTED MANUSCRIPTS
        $sql = "SELECT * FROM manuscript WHERE status = 1 AND NOT EXISTS (SELECT * FROM manuscript_token WHERE manuscript.id = manuscript_token.manuscriptID AND manuscript_token.userID = ?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('s', $srCode);
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
                "<a href='#viewJournalModal' class='view-journal' data-bs-toggle='modal' data-id = '" . $id . "' data title='Click to view: " . $manuscriptTitle . "'>" . $manuscriptTitle . "</a>",
                str_replace(",", "<br>", $author) ?? $author,
                $yearPub,
                $campusName,
                $departmentName,
                $dateUploaded = (new DateTime($dateUploaded))->format('F d, Y - h:i A'),
                '   <button type="button" class="btn btn-success btn-sm approved-pending" data-id="' . $id . '">APPROVE</button>
                    <button data-bs-target="#reasonModal" data-bs-toggle="modal" class="btn btn-danger btn-sm" data-id="' . $id . '">DECLINE</button>
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
                t.id,
                t.manuscriptID,
                t.userID,
                t.time,
                m.manuscriptTitle,
                m.author,
                CONCAT(u.firstName, ' ' , u.middleName , ' ' , u.lastName) AS name
                FROM manuscript_token t
                LEFT JOIN manuscript m ON t.manuscriptID = m.id
                LEFT JOIN user_details u ON t.userID = u.id
                WHERE t.status = 0";
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
                $time = (new DateTime($time))->format('F d, Y - h:i A'),
                $manuscriptTitle,
                str_replace(",", "<br>", $author) ?? $author,
                $name,
                '
                    <button type="button" class="btn btn-success btn-sm approve-request" data-id="' . $id . '" data-bs-toggle="modal" data-bs-target="">APPROVE</button>
                    <button type="button" class="btn btn-danger btn-sm decline-request" data-id="' . $id . '">DECLINE</button>
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

    public function getManuscriptBySrCode($srCode)
    {
        $sql = "SELECT 
                id,
                manuscriptTitle,
                dateUploaded,
                status  
                FROM manuscript 
                WHERE srCode = ?";
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

    public function requestManuscript($srCode, $manuscriptId)
    {
        $token = $this->createToken();
        $sql = "INSERT INTO manuscript_token(id, manuscriptID, userID, status, token, dateApproved, time) VALUES (NULL, ?, ?, 0, ?, 0, 0)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("iss", $manuscriptId, $srCode, $token);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $data = [
                "success" => true,
                "message" => "Inserted successfully",
                "error" => "Something went wrong! Error: " . $stmt->error
            ];
        } else {
            $data = [
                "success" => false,
                "message" => "Error inserting",
                "error" => "Something went wrong! Error: " . $stmt->error
            ];
        }
        return json_encode($data);
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

    public function updatePendingManuscript($manuscriptId, $status, $date)
    {
        $sql = "UPDATE manuscript SET status = ?, actionDate = ? WHERE id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("isi", $status, $date, $manuscriptId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return $this->insertNotification($manuscriptId,  $status = ($status == 1) ? "Approved" : "Declined");
        } else {
            return 0;
        }

        return $this->insertNotification($manuscriptId,  $status = ($status == 1) ? "Approved" : "Declined");
    }

    private function insertNotification($manuscriptId, $status, $request = "", $reason = "")
    {
        $title = $this->getManuscriptTitle($manuscriptId);
        $id = $this->getSRCodeByNames($this->getAuthorByID($manuscriptId));

        if ($request == "") {
            $type = ($status == "Approved") ? $this->typeID[2] : $this->typeID[3];
        } else {
            $type = (($status == "") ? $this->typeID[4] : ($status == "Approved")) ? $this->typeID[5] : $this->typeID[6];
        }

        $message = $title;

        if ($request == "") {
            $message .= ($status == "Approved") ? $this->messages[2] : $this->messages[3] . $reason;
        } else {
            $message .= (($status == "") ? $title . $this->messages[4] : ($status == "Approved")) ? $title . $this->messages[5] : $title . $this->messages[6] . $reason;
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

        return json_encode($data);
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

    public function updateManuscriptRequest($id, $status, $request)
    {
        $sql = "UPDATE manuscript_token SET status = ?, dateApproved = now() WHERE id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("ii", $status, $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return $this->insertNotification($id, $status = ($status == 1) ? "Approved" : "Declined", $request);
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
}
