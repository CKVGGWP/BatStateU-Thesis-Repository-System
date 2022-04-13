<!DOCTYPE html>
<html lang="en">
<?php include "views/includes/head.php"; ?>
<?php include "controllers/emptySessionController.php"; ?>
<?php include "controllers/informationController.php"; ?>

<body>
    <?php include "views/dashboard/header.php"; ?>

    <?php include "views/includes/sidebar.php"; ?>

    <?php if ($_SESSION['role'] == '1') : ?>
        <?php include "views/dashboard/dashboardAdmin.php"; ?>
    <?php else : ?>
        <?php include "views/dashboard/dashboardUser.php"; ?>
    <?php endif; ?>

</body>

<?php include "views/includes/footer.php"; ?>
<script src="./assets/js/information.js"></script>

</html>