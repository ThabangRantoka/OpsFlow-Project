<?php
/*
======================================
OpsFlow - Delete Attendance Record
======================================
*/

require_once("../config/auth.php");
require_once("../config/DataBase.php");
require_once("../config/permissions.php");
require_once("../config/functions.php");

requireRole(["Admin", "Manager"]);

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

if ($id <= 0) {
    header("Location:index.php");
    exit();
}

$stmt = $conn->prepare("DELETE FROM attendance WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {

    logActivity(
        $conn,
        $_SESSION["user_id"],
        $_SESSION["fullname"],
        "Deleted attendance record #" . $id
    );

    header("Location:index.php?msg=" . urlencode("Attendance record deleted."));
    exit();
}

header("Location:index.php?msg=" . urlencode("Could not delete the attendance record."));
exit();
