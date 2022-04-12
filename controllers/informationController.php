<?php

include('models/database.php');
include('models/information.php');

$info = new Information();

$id = isset($_SESSION['srCode']) ? $_SESSION['srCode'] : '';

$campuses = $info->getCampuses();

$user = $info->getUserBySession($id);

$name = $user['firstName'] . ' ' . $user['lastName'];
$role = ($user['role'] == '1') ? 'Admin' : 'User';
$title = ($user['role'] == '1') ? 'Librarian' : 'Student';
