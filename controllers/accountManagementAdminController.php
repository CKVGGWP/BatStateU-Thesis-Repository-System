<?php

include('../models/database.php');
include('../models/accounts.php');

$accounts = new Accounts();

if (isset($_POST['getAccounts'])) {
    echo $accounts->getAccountsTable();
}
