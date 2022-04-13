<?php

include('../models/database.php');
include('../models/upload.php');

$upload = new Upload();
if (isset($_POST['uploadAdmin'])) {
    echo $_POST;
    // echo $upload->uploadFiles();
}

// echo $title;



