<?php

require_once("../config/auth.php");

require_once("../config/DataBase.php");

require_once("../config/permissions.php");

requireRole("Admin");


$id = intval($_GET["id"]);


// Don't deactivate yourself
if($id == $_SESSION["user_id"]){


    die("You cannot deactivate your own account.");


}


$stmt = $conn->prepare("
UPDATE users
SET status='Inactive'
WHERE id=?
");


$stmt->bind_param("i",$id);


$stmt->execute();


header("Location:index.php");

exit();


?>