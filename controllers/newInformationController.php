<?php

include('../models/database.php');
include('../models/information.php');

$info = new Information();

if (isset($_POST['getCampus'])) {
    $campus = $_POST['campus'];
    echo $info->getDeptByCampus($campus);
}
