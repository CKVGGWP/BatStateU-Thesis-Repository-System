<!DOCTYPE html>
<html lang="en">

<?php include "views/includes/head.php"; ?>
<?php include "controllers/indexController.php"; ?>

<body onload='$(".loader").fadeOut(400);'>
    <div class="loader"></div>
    <?php include "views/resetPass/resetPassword.php"; ?>

</body>

<?php include "views/includes/footer.php"; ?>
<script src="./assets/js/loginRegister.js"></script>

</html>