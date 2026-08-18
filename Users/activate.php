<?php

require_once("../config/auth.php");
require_once("../config/DataBase.php");
require_once("../config/permissions.php");
requireRole("Admin");

$id = intval($_GET["id"]);

$stmt = $conn->prepare("
UPDATE users
SET status='Active'
WHERE id=?
");

$stmt->bind_param("i",$id);

$stmt->execute();

header("Location:index.php");
exit();

?>