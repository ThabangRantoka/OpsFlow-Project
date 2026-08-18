<?php

require_once("../config/auth.php");

require_once("../config/permissions.php");


requireRole("Admin");

require_once("../config/DataBase.php");


if(!isset($_GET["id"])){


    header("Location:index.php");

    exit();


}


$id = intval($_GET["id"]);


// Prevent deleting yourself
if($id == $_SESSION["user_id"]){


    die("You cannot delete your own account.");


}


$stmt = $conn->prepare("DELETE FROM users WHERE id=?");

$stmt->bind_param("i",$id);


if($stmt->execute()){


    header("Location:index.php");


}
else{


    echo $conn->error;


}

?>