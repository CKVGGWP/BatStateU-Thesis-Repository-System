<!DOCTYPE html>
<html lang="en">

<?php include "views/head.php"; ?>
<?php include "controllers/indexController.php"; ?>

<body onload='$(".loader").fadeOut(400);'>
    <div class="loader"></div>
    <?php include "views/resetPassword.php"; ?>

</body>

<?php include "views/footer.php"; ?>
<script src="./assets/js/loginRegister.js"></script>

</html>