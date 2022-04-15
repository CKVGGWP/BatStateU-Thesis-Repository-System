<?php

include('../models/database.php');
include('../models/manuscript.php');

$manuscript = new Manuscript();
if (isset($_POST['getManuscript'])) {
    echo $manuscript->getManuscriptTable();
}

if (isset($_POST['browseManuscript'])) {
    echo $manuscript->getBrowseManuscriptTable();
}

if (isset($_POST['getRequestAdmin'])) {
    echo $manuscript->getRequestAdminTable();
}

if (isset($_POST['pendingManuscript'])) {
    echo $manuscript->getPendingManuscriptTable();
}

if (isset($_POST['manuscriptDetails'])) {
    $manuscriptId = $_POST['manuscriptId'];
    echo $manuscript->getManuscriptDetails($manuscriptId);
}

if (isset($_POST['deleteManuscript'])) {
    $manuscriptId = $_POST['manuscriptId'];
    echo $manuscript->deleteManuscript($manuscriptId);
}

if (isset($_POST['udpateManuscript'])) {
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

if (isset($_POST['approveManuscript'])) {
    $manuscriptId = $_POST['manuscriptId'];
    echo $manuscript->approveManuscript($manuscriptId);
}