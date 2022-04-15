<?php

if (!empty($_SESSION)) :
    header('Location: dashboard.php?title=Dashboard');
endif;
