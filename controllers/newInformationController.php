<?php

include('../models/database.php');
include('../models/information.php');

session_start();

$info = new Information();

$srCode = isset($_SESSION['srCode']) ? $_SESSION['srCode'] : '';

if (isset($_POST['getCampus'])) {
    $campus = $_POST['campus'];
    echo $info->getDeptByCampus($campus);
}

if (isset($_POST['saveInfo'])) {
    $data = array(
        'email'         =>      $_POST['email'],
        'firstName'     =>      $_POST['firstName'],
        'middleName'    =>      $_POST['middleName'],
        'lastName'      =>      $_POST['lastName'],
        'campus'        =>      $_POST['userCampus'],
        'department'    =>      $_POST['userDepartment'],
        'srCode'        =>      $_POST['srCode']
    );

    echo $info->updateInfo($data);
}

if (isset($_POST['changePass'])) {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];

    echo $info->changePassword($currentPassword, $newPassword, $srCode);
}

if (isset($_POST['view'])) {
    if ($_POST['view'] != "") {
        echo $info->getNotification($srCode, "Update");
    }
    echo $info->getNotification($srCode);
}

if (isset($_POST['markAll'])) {
    echo $info->updateNotification($srCode);
}
