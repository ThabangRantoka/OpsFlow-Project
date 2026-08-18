<?php
/*
======================================
OpsFlow Enterprise Management System
Logout
Developer : Moyahabo Thabang
Version : 2.0
======================================
*/

session_start();


require_once("../config/DataBase.php");

require_once("../config/functions.php");


// Log logout activity before destroying the session
if(isset($_SESSION["user_id"])){


    logActivity(
        $conn,
        $_SESSION["user_id"],
        $_SESSION["fullname"],
        "Logged out of the system"
    );


}


// Remove all session variables
session_unset();


// Destroy the session
session_destroy();


// Redirect to login page
header("Location: login.php");

exit();


?>