<?php

if (!empty($_SESSION)) :
    if ($_SESSION['role'] == '0') :
        header('Location: dashboardUser.php');
    elseif ($_SESSION['role'] == '1') :
        header('Location: dashboardAdmin.php');
    endif;
endif;
