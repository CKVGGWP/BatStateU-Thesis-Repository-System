<?php

include('models/database.php');
include('models/information.php');

$info = new Information();

$id = isset($_SESSION['srCode']) ? $_SESSION['srCode'] : '';

$campuses = $info->getCampuses();

if ($id != '') {
    $user = $info->getUserBySession($id);

    $name = $user['firstName'] . ' ' . $user['lastName'];
    $firstName = $user['firstName'];
    $middleName = $user['middleName'];
    $lastName = $user['lastName'];
    $role = ($user['role'] == '1') ? 'Admin' : 'User';
    $title = ($user['role'] == '1') ? 'Librarian' : 'Student';
    $srCode = $user['srCode'];
    $email = $user['email'];
    $campus = $user['campusName'];
    $department = $user['departmentName'];
    $deptID = $user['deptID'];
    $campID = $user['campID'];

    $campusNotIncluded = $info->getCampuses($campID);
}
