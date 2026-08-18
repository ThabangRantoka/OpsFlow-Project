<?php
/**
 * ==========================================================
 * OpsFlow Enterprise Management System
 * File: Departments/save.php
 * Description: Saves a new department.
 * Developer: Moyahabo Thabang
 * Version: 2.0
 * ==========================================================
 */

require_once("../config/auth.php");
require_once("../config/DataBase.php");
require_once("../config/functions.php");

require_once("../config/permissions.php");

requireRole(["Admin","Manager"]);

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: add.php");
    exit();
}

// Get form data
$department_name = trim($_POST["department_name"]);
$department_code = strtoupper(trim($_POST["department_code"]));
$description = trim($_POST["description"]);

// Check duplicates
$check = $conn->prepare("
SELECT id
FROM departments
WHERE department_name = ?
OR department_code = ?
");

$check->bind_param(
    "ss",
    $department_name,
    $department_code
);

$check->execute();

$result = $check->get_result();

if ($result->num_rows > 0) {

    echo "<script>
        alert('Department already exists!');
        window.location='add.php';
    </script>";

    exit();
}

// Save department
$sql = "
INSERT INTO departments
(
department_name,
department_code,
description
)
VALUES
(
?,
?,
?
)
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sss",
    $department_name,
    $department_code,
    $description
);

if ($stmt->execute()) {

    logActivity(
        $conn,
        $_SESSION["user_id"],
        $_SESSION["fullname"],
        "Added department: " . $department_name
    );

    header("Location: index.php");
    exit();

} else {

    die("Database Error: " . $conn->error);

}

$stmt->close();
$conn->close();

?>