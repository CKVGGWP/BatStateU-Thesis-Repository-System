<?php

session_start();

if (empty($_SESSION['srCode'])) {
    header("Location: index.php");
}
