<!DOCTYPE html>
<html lang="en">
<?php include "views/includes/head.php"; ?>
<?php include "controllers/indexController.php"; ?>

<body onload='$(".loader").fadeOut(400);'>
    <div class="loader"></div>

    <?php if (!isset($_GET['title'])) : ?>

        <?php include "views/login/login.php"; ?>

    <?php else : ?>

        <?php if ($_GET['title'] == "Create Account") : ?>

            <?php include "views/register/register.php"; ?>

        <?php elseif ($_GET['title'] == "Reset Password") : ?>

            <?php include "views/resetPass/resetPassword.php"; ?>

        <?php elseif ($_GET['title'] == "Verify Account") : ?>

        <?php endif; ?>

    <?php endif; ?>
</body>
<?php include "views/includes/footer.php"; ?>
<script src="./assets/js/loginRegister.js" defer></script>
<script src="./assets/js/information.js" defer></script>

</html>