<?php

include('../models/database.php');
include('../models/accounts.php');

session_start();

$session = isset($_SESSION['srCode']) ? $_SESSION['srCode'] : '';

$accounts = new Accounts();
if (isset($_POST['getAccounts'])) {
    $title = $_POST['title'];

    if ($title == "Account Management") {
        echo $accounts->getAccountsTable(0);
    } else {
        echo $accounts->getAccountsTable(1);
    }
}

// Get Account Details
if (isset($_POST['getAccountDetails'])) {
    $srCode = $_POST['srCode'];
    echo $accounts->getAccountDetails($srCode);
}
// Delete Account
if (isset($_POST['deleteAccount'])) {
    $srCode = $_POST['srCode'];
    echo $accounts->deleteAccount($srCode, $session);
}
