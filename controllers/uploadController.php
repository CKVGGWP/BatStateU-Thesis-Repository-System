<?php

include('../models/database.php');
include('../models/upload.php');

$upload = new Upload();
if (isset($_POST['uploadAdmin'])) {
    print_r($_POST);
    print_r($_FILES);
    // echo $upload->uploadFiles($_POST, $_FILES);
}
