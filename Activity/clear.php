<?php

require_once("../config/auth.php");
require_once("../config/DataBase.php");

$conn->query("TRUNCATE TABLE activity_logs");

header("Location:index.php");
exit();

?>