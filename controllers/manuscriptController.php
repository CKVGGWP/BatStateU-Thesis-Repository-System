<?php

include('../models/database.php');
include('../models/manuscript.php');

$manuscript = new Manuscript();
if (isset($_POST['getManuscript'])) {
    echo $manuscript->getManuscriptTable();
}

if (isset($_POST['getRequestAdmin'])) {
    echo $manuscript->getRequestAdminTable();
}

