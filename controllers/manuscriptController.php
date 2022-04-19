<?php

include('../models/database.php');
include('../models/manuscript.php');

session_start();

$usersID = isset($_SESSION['srCode']) ? $_SESSION['srCode'] : '';

$manuscript = new Manuscript();

if (isset($_POST['getManuscript'])) {
    echo $manuscript->getManuscriptTable();
}

if (isset($_POST['browseManuscript'])) {
    $srCode = $_SESSION['srCode'];
    echo $manuscript->getBrowseManuscriptTable($srCode);
}

if (isset($_POST['getRequestHistory'])) {
    echo $manuscript->getRequestHistoryTable();
}

if (isset($_POST['getRequestAdmin'])) {
    echo $manuscript->getRequestAdminTable();
}

if (isset($_POST['pendingManuscript'])) {
    $srCode = $_SESSION['srCode'];
    echo $manuscript->getPendingManuscriptTable($srCode);
}

if (isset($_POST['requestManuscript'])) {
    $srCode = $_SESSION['srCode'];
    $manuscriptId = $_POST['manuscriptId'];
    echo $manuscript->requestManuscript($srCode, $manuscriptId);
}

if (isset($_POST['manuscriptDetails'])) {
    $manuscriptId = $_POST['manuscriptId'];
    echo $manuscript->getManuscriptDetails($manuscriptId);
}

if (isset($_POST['deleteManuscript'])) {
    $manuscriptId = $_POST['manuscriptId'];
    echo $manuscript->deleteManuscript($manuscriptId);
}

if (isset($_POST['updateManuscript'])) {
    $data = array(
        'manuscriptId'        =>     $_POST['manuscriptId'],
        'manuscriptTitle'     =>     $_POST['manuscriptTitle'],
        'manuscriptAuthors'   =>     $_POST['manuscriptAuthors'],
        'manuscriptYearPub'   =>     $_POST['manuscriptYearPub'],
        'manuscriptCampus'    =>     $_POST['manuscriptCampus'],
        'manuscriptDept'      =>     $_POST['manuscriptDept']

    );
    echo $manuscript->updateManuscript($data);
}

if (isset($_POST['updatePendingManuscript'])) {
    $manuscriptId = $_POST['manuscriptId'];
    $status = $_POST['status'];
    $date = date('Y-m-d H:i:s');
    $reason = isset($_POST['reason']) ? $_POST['reason'] : '';
    echo $manuscript->updatePendingManuscript($manuscriptId, $status, $date, $reason);
}

if (isset($_POST['getUserManuscript'])) {
    $srCode = $_SESSION['srCode'];
    echo $manuscript->getManuscriptBySrCode($srCode);
}

if (isset($_POST['manuscriptRequest'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $reason = isset($_POST['reason']) ? $_POST['reason'] : '';
    echo $manuscript->updateManuscriptRequest($id, $status, $reason);
}

if (isset($_POST['getPendingManuscript'])) {
    echo $manuscript->getManuscriptButton();
}

if (isset($_POST['getRecentAddedManuscript'])) {
    $recent = $_POST['recent'];
    echo $manuscript->getManuscriptTable($recent);
}

if (isset($_POST['userManuscriptRequestStatus'])) {
    $srCode = $_SESSION['srCode'];
    echo $manuscript->getRequestAdminTable($srCode);
}

if (isset($_POST['getPendingByGroup'])) {
    $srCode = $_SESSION['srCode'];
    echo $manuscript->getGroupNumber($srCode);
}

if (isset($_POST['checkManuscriptPassword'])) {
    $pass = $_POST['password'];

    echo $manuscript->checkPassword($pass, $usersID);
}
