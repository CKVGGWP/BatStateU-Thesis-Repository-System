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

        //REQUESTED MANUSCRIPT
        $sql = "SELECT * FROM manuscript WHERE status = 1 AND EXISTS (SELECT * FROM manuscript_token WHERE manuscript.id = manuscript_token.manuscriptID AND manuscript_token.status = '1' AND manuscript_token.userID = ?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('i', $userId);
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
                $tags,
            ];
        }

        //PENDING MANUSCRIPTS
        $sql = "SELECT * FROM manuscript WHERE status = 1 AND EXISTS (SELECT * FROM manuscript_token WHERE manuscript.id = manuscript_token.manuscriptID AND manuscript_token.status = '0' AND manuscript_token.userID = ?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('i', $userId);
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
        $sql = "SELECT * FROM manuscript WHERE status = 1 AND EXISTS (SELECT * FROM manuscript_token WHERE manuscript.id = manuscript_token.manuscriptID AND manuscript_token.status = '2' AND manuscript_token.userID = ?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('i', $userId);
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
                $tags,
            ];
        }

        //NOT REQUESTED MANUSCRIPTS
        $sql = "SELECT * FROM manuscript WHERE status = 1 AND NOT EXISTS (SELECT * FROM manuscript_token WHERE manuscript.id = manuscript_token.manuscriptID AND manuscript_token.userID = ?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param('i', $userId);
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
                t.id,
                t.manuscriptID,
                t.userID,
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
                    $time = (new DateTime($time))->format('F d, Y - h:i A'),
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
                t.id,
                t.manuscriptID,
                t.userID,
                t.time,
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
                $time = (new DateTime($time))->format('F d, Y - h:i A'),
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

    public function deleteManuscript($manuscriptId)
    {
        $this->deleteFiles($this->getManuscriptFiles($manuscriptId));
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
        $id = $this->getID($srCode);
        $sql = "INSERT INTO manuscript_token(id, manuscriptID, userID, status, token, dateApproved, time) VALUES (NULL, ?, ?, 0, ?, 0, 0)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("iis", $manuscriptId, $id, $token);
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

        $type = ($status == "Approved") ? $this->typeID[5] : $this->typeID[6];

        $message = $title;

        $message .= ($status == "Approved") ? $this->messages[5] : $this->messages[6] . $reason;

        $redirect = $this->redirect[4];

        $dateNow = dateTimeNow();

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

    private function getManuscriptTokenData($id)
    {
        $sql = "SELECT 
                groupID,
                userID,
                manuscriptID
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
}
