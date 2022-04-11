<?php

include('models/database.php');
include('models/information.php');

$info = new Information();

$campuses = $info->getCampuses();
