<?php

require_once("../config/auth.php");
require_once("../config/DataBase.php");
require_once("../config/permissions.php");

requireRole(["Admin","Manager"]);

if($_SERVER["REQUEST_METHOD"]!="POST"){
    header("Location:index.php");
    exit();
}

$id = intval($_POST["id"]);

$project_code = trim($_POST["project_code"]);
$project_name = trim($_POST["project_name"]);
$department = trim($_POST["department"]);
$project_manager = trim($_POST["project_manager"]);
$start_date = $_POST["start_date"];
$end_date = $_POST["end_date"];
$budget = $_POST["budget"];
$progress = $_POST["progress"];
$priority = $_POST["priority"];
$status = $_POST["status"];

$stmt = $conn->prepare("
UPDATE projects
SET
project_code=?,
project_name=?,
department=?,
project_manager=?,
start_date=?,
end_date=?,
budget=?,
progress=?,
priority=?,
status=?
WHERE id=?
");

$stmt->bind_param(
"ssssssdissi",
$project_code,
$project_name,
$department,
$project_manager,
$start_date,
$end_date,
$budget,
$progress,
$priority,
$status,
$id
);

if($stmt->execute()){

    header("Location:index.php");
    exit();

}else{

    die("Error updating project.");

}