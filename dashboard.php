<!DOCTYPE html>
<html lang="en">
<?php include "views/includes/head.php"; ?>
<?php include "controllers/emptySessionController.php"; ?>
<?php include "controllers/informationController.php"; ?>

<body onload='$(".loader").fadeOut(400);'>

    <div class="loader"></div>

    <?php if (isset($_GET['title'])) : ?>

        <?php include "views/dashboard/header.php"; ?>

        <?php include "views/includes/sidebar.php"; ?>

        <?php if ($_GET['title'] == "Dashboard") : ?>

            <?= print_r($_SESSION); ?>

            <?php if ($_SESSION['role'] == '1') : ?>

                <?php include "views/dashboard/dashboardAdmin.php"; ?>

            <?php else : ?>

                <?php include "views/dashboard/dashboardUser.php"; ?>

            <?php endif; ?>

        <?php elseif ($_GET['title'] == "Account Management") : ?>

            <?php include "views/account/accountManagementAdmin.php"; ?>

        <?php elseif ($_GET['title'] == "Admin Management") : ?>

            <?php include "views/account/adminManagement.php"; ?>

        <?php elseif ($_GET['title'] == "View Request") : ?>

            <?php include "views/dashboard/requestAdmin.php"; ?>

        <?php elseif ($_GET['title'] == "View Manuscript") : ?>

            <?php include "views/dashboard/viewAdmin.php"; ?>

        <?php elseif ($_GET['title'] == "Browse Manuscript") : ?>

            <?php include "views/dashboard/searchUser.php"; ?>

        <?php elseif ($_GET['title'] == "Upload Manuscript") : ?>

            <?php include "views/upload/upload.php"; ?>

        <?php elseif ($_GET['title'] == "Account Settings") : ?>

            <?php include "views/account/accountSetting.php"; ?>

        <?php elseif ($_GET['title'] == "Pending Manuscripts") : ?>

            <?php include "views/dashboard/pendingAdmin.php"; ?>

        <?php endif; ?>

    <?php else : ?>

        <?php include "views/404/error404.php"; ?>

    <?php endif; ?>

</body>

<?php include "views/includes/footer.php"; ?>
<script src="./assets/js/information.js"></script>
<script src="./assets/js/accountManagementAdmin.js"></script>
<script src="./assets/js/manuscript.js"></script>
<script src="./assets/js/upload.js"></script>
<script src="./assets/js/dashboard.js"></script>

</html>