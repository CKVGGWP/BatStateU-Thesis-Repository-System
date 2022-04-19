<?php

include('../models/database.php');
include('../models/information.php');

session_start();

$info = new Information();

$srCode = isset($_SESSION['srCode']) ? $_SESSION['srCode'] : '';

if (isset($_POST['getCampus'])) {
    $campus = $_POST['campus'];
    echo $info->getDeptByCampus($campus, "options");
}

if (isset($_POST['getDepartment'])) {
    $department = $_POST['department'];
    echo $info->getProgByDept($department, "options");
}

if (isset($_POST['saveInfo'])) {
    $data = array(
        'email'         =>      $_POST['email'],
        'firstName'     =>      $_POST['firstName'],
        'middleName'    =>      $_POST['middleName'],
        'lastName'      =>      $_POST['lastName'],
        'campus'        =>      $_POST['userCampus'],
        'department'    =>      isset($_POST['userDepartment']) ? $_POST['userDepartment'] : '',
        'program'       =>      isset($_POST['userProgram']) ? $_POST['userProgram'] : '',
        'srCode'        =>      $_POST['srCode']
    );

    echo $info->updateInfo($data);
}

if (isset($_POST['createAdmin'])) {
    $data = array(
        'id'            =>      $_POST['createID'],
        'email'         =>      $_POST['createEmail'],
        'firstName'     =>      $_POST['createFirstName'],
        'middleName'    =>      $_POST['createMiddleName'],
        'lastName'      =>      $_POST['createLastName'],
        'campus'        =>      $_POST['createCampus'],
        'password'      =>      $_POST['createPassword'],
    );

    echo $info->createAdmin($data);
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

if (isset($_POST['getAllUsers'])) {
    echo $info->getAllUsers();
}

if (isset($_POST['getIP'])) {
    $ip = $_POST['ip'];

    echo $info->insertIP($ip);
}

if (isset($_POST['ipChart'])) {
    echo $info->getIPChart();
}

if (isset($_POST['getProgram'])) {
    $deptID = $_POST['department'];

    echo $info->getProgram($deptID);
}

if (isset($_POST['getUsers'])) {
    $dept = $_POST['department'];
    $program = $_POST['program'];

    echo $info->getUsersByDeptProgram($dept, $program);
}
