<?php

require_once("DataBase.php");
require_once("notifications.php");

$result = createNotification(
    $conn,
    6,
    "Test Notification",
    "This is only a test.",
    "🔔"
);

if($result){
    echo "Notification inserted successfully!";
}else{
    echo "Insert failed!";
}