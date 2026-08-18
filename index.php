<?php
/*
======================================
OpsFlow - Application entry point
======================================
*/

session_start();

if (isset($_SESSION["user_id"])) {
    header("Location: Auth/login.php");
} else {
    header("Location: Auth/login.php");
}

exit();
