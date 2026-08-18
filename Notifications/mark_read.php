<?php

require_once("../config/auth.php");

require_once("../config/DataBase.php");


$id = intval($_GET["id"]);


$stmt = $conn->prepare("
UPDATE notifications
SET is_read=1
WHERE id=?
");


$stmt->bind_param("i",$id);

$stmt->execute();


header("Location:index.php");

exit();


?>