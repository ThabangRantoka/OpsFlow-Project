<?php

require_once("../config/auth.php");
require_once("../config/DataBase.php");
require_once("../config/permissions.php");
requireRole(["Admin","Manager"]);

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php");
    exit();
}

// Get form data
$id = $_POST["id"];
$employee_number = trim($_POST["employee_number"]);
$fullname = trim($_POST["fullname"]);
$gender = $_POST["gender"];
$email = trim($_POST["email"]);
$phone = trim($_POST["phone"]);
$department = trim($_POST["department"]);
$position = trim($_POST["position"]);
$salary = $_POST["salary"];
$hire_date = $_POST["hire_date"];
$status = $_POST["status"];

// Check if another employee already uses this employee number or email
$check = $conn->prepare("
SELECT id
FROM employees
WHERE (employee_number = ? OR email = ?)
AND id != ?
");

$check->bind_param(
    "ssi",
    $employee_number,
    $email,
    $id
);

$check->execute();

$result = $check->get_result();

if ($result->num_rows > 0) {

    echo "<script>
            alert('Employee Number or Email already exists!');
            window.history.back();
          </script>";

    exit();
}

// Update employee
$sql = "
UPDATE employees
SET
employee_number=?,
fullname=?,
gender=?,
email=?,
phone=?,
department=?,
position=?,
salary=?,
hire_date=?,
status=?
WHERE id=?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sssssssdssi",
    $employee_number,
    $fullname,
    $gender,
    $email,
    $phone,
    $department,
    $position,
    $salary,
    $hire_date,
    $status,
    $id
);

if ($stmt->execute()) {

    header("Location: index.php?updated=1");
    exit();

} else {

    die("Update failed: " . $conn->error);

}
?>