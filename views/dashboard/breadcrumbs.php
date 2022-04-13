<?php

$filePath = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);

?>
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <?php if ($filePath == "dashboard.php") : ?>
        <li class="breadcrumb-item active">Dashboard</li>
    <?php elseif ($filePath == "requestAdmin.php") : ?>
        <li class="breadcrumb-item">Manuscript</li>
        <li class="breadcrumb-item active">Requests</li>
    <?php elseif ($filePath == "accountSetting.php") : ?>
        <li class="breadcrumb-item active">Account Settings</li>
    <?php elseif ($filePath == "accountManagementAdmin.php") : ?>
        <li class="breadcrumb-item">Accounts</li>
        <li class="breadcrumb-item active">Account Management</li>
    <?php elseif ($filePath == "uploadAdmin.php") : ?>
        <li class="breadcrumb-item">Manuscript</li>
        <li class="breadcrumb-item active">Upload</li>
    <?php elseif ($filePath == "viewAdmin.php") : ?>
        <li class="breadcrumb-item active">Manuscript</li>
    <?php elseif ($filePath == "searchUser.php") : ?>
        <li class="breadcrumb-item active">Browse Manuscript</li>
        <?php elseif ($filePath == "pending.php") : ?>
        <li class="breadcrumb-item">Manuscript</li>
        <li class="breadcrumb-item active">Pending Manuscript</li>
    <?php endif; ?>
</ol>