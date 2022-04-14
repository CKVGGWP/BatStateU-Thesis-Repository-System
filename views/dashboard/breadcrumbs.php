<?php

// $filePath = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);

$title = isset($_GET['title']) ? $_GET['title'] : 'Dashboard';

?>
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php?title=Dashboard">Home</a></li>
    <?php if ($title == "Dashboard") : ?>
        <li class="breadcrumb-item active">Dashboard</li>
    <?php elseif ($title == "View Request") : ?>
        <li class="breadcrumb-item">Manuscript</li>
        <li class="breadcrumb-item active">Requests</li>
    <?php elseif ($title == "Account Settings") : ?>
        <li class="breadcrumb-item active">Account Settings</li>
    <?php elseif ($title == "Account Management") : ?>
        <li class="breadcrumb-item">Accounts</li>
        <li class="breadcrumb-item active">Account Management</li>
    <?php elseif ($title == "Upload Manuscript") : ?>
        <li class="breadcrumb-item">Manuscript</li>
        <li class="breadcrumb-item active">Upload</li>
    <?php elseif ($title == "View Manuscript") : ?>
        <li class="breadcrumb-item active">Manuscript</li>
    <?php elseif ($title == "Browse Manuscript") : ?>
        <li class="breadcrumb-item active">Browse Manuscript</li>
    <?php elseif ($title == "Pending Manuscripts") : ?>
        <li class="breadcrumb-item">Manuscript</li>
        <li class="breadcrumb-item active">Pending Manuscript</li>
    <?php endif; ?>
</ol>