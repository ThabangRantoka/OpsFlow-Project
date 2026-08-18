<?php
/*
======================================
OpsFlow - Delete Project
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


/* Get the project name first (for the activity log) */
$stmt = $conn->prepare("SELECT project_name FROM projects WHERE id=?");

$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows === 0) {

    header("Location:index.php?msg=" . urlencode("Project not found."));

    exit();

}


$project = $result->fetch_assoc();


$del = $conn->prepare("DELETE FROM projects WHERE id=?");

$del->bind_param("i", $id);


if ($del->execute()) {


    logActivity(
        $conn,
        $_SESSION["user_id"],
        $_SESSION["fullname"],
        "Deleted project: " . $project["project_name"]
    );


    header("Location:index.php?msg=" . urlencode("Project deleted successfully."));

    exit();

}


header("Location:index.php?msg=" . urlencode("Could not delete the project."));

exit();

