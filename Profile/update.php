<?php

require_once("../config/auth.php");
require_once("../config/DataBase.php");

$id = $_SESSION["user_id"];

$current = $conn->prepare("SELECT photo FROM users WHERE id=?");
$current->bind_param("i",$id);
$current->execute();

$data = $current->get_result()->fetch_assoc();

$photo = $data["photo"];

if(isset($_FILES["photo"]) && $_FILES["photo"]["error"]==0){

    $photo = time()."_".basename($_FILES["photo"]["name"]);

    move_uploaded_file(

        $_FILES["photo"]["tmp_name"],

        "photos/".$photo

    );

}

$stmt = $conn->prepare("

UPDATE users

SET

fullname=?,
email=?,
phone=?,
photo=?

WHERE id=?

");

$stmt->bind_param(

"ssssi",

$_POST["fullname"],
$_POST["email"],
$_POST["phone"],
$photo,
$id

);

$stmt->execute();

$_SESSION["fullname"]=$_POST["fullname"];

header("Location:index.php");
exit();

?>