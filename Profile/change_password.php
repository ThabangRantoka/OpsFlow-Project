<?php

require_once("../config/auth.php");

require_once("../config/DataBase.php");


$id = $_SESSION["user_id"];


$stmt = $conn->prepare("SELECT password FROM users WHERE id=?");

$stmt->bind_param("i",$id);

$stmt->execute();


$user = $stmt->get_result()->fetch_assoc();


if(!password_verify($_POST["current_password"],$user["password"])){


die("Current password is incorrect.");


}


if($_POST["new_password"] != $_POST["confirm_password"]){


die("Passwords do not match.");


}


$newPassword = password_hash(

$_POST["new_password"],

PASSWORD_DEFAULT

);


$update = $conn->prepare("

UPDATE users

SET password=?

WHERE id=?

");


$update->bind_param(

"si",

$newPassword,

$id

);


$update->execute();


header("Location:index.php");


exit();


?>