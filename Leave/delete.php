<?php
require_once("../config/auth.php");
require_once("../config/permissions.php");
require_once("../config/DataBase.php");

requireRole(["Admin","Manager"]);

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM leave_requests WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: index.php?success=" . urlencode("Leave request deleted."));
exit();
