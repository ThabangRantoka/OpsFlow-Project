<?php

require_once("../config/auth.php");
require_once("../config/DataBase.php");

require_once("../config/permissions.php");

requireRole("Admin");

// Check if ID exists
if(!isset($_GET["id"])){

    header("Location:index.php");
    exit();

}

$id = intval($_GET["id"]);

// Delete employee
$sql = "DELETE FROM employees WHERE id=?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i",$id);

if($stmt->execute()){

    header("Location:index.php?deleted=1");
    exit();

}else{

    die("Delete failed.");

}

?>