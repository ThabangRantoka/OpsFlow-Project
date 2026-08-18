<?php
/*
==========================================
OpsFlow Enterprise Management System
Attendance/edit.php
Developer: Moyahabo Thabang
Version: 2.0
==========================================
*/

require_once("../config/auth.php");
require_once("../config/DataBase.php");
require_once("../config/permissions.php");

requireRole(["Admin","Manager"]);

if(!isset($_GET["id"]) || !is_numeric($_GET["id"])){

    header("Location:index.php");
    exit();

}

$id = (int)$_GET["id"];

/* Get Attendance Record */

$stmt = $conn->prepare("
SELECT *
FROM attendance
WHERE id=?
");

$stmt->bind_param("i",$id);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows==0){

    die("Attendance record not found.");

}

$attendance = $result->fetch_assoc();

/* Load Employees */

$employees = $conn->query("
SELECT
id,
employee_number,
fullname
FROM employees
ORDER BY fullname ASC
");
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Attendance</title>

<link rel="stylesheet" href="../Assets/CSS/style.css">
<link rel="stylesheet" href="../Assets/CSS/dashboard.css">

</head>

<body>

<div class="dashboard">

<?php include("../Includes/sidebar.php"); ?>

<div class="main-content">

<?php include("../Includes/header.php"); ?>

<div class="recent">

<h2>Edit Attendance</h2>

<br>

<form action="update.php" method="POST">

<input
type="hidden"
name="id"
value="<?php echo $attendance["id"]; ?>">

<label>Employee</label>

<select name="employee_id" required>

<?php while($emp = $employees->fetch_assoc()){ ?>

<option
value="<?php echo $emp["id"]; ?>"
<?php if($attendance["employee_id"]==$emp["id"]) echo "selected"; ?>>

<?php
echo htmlspecialchars(
$emp["employee_number"] .
" - " .
$emp["fullname"]
);
?>

</option>

<?php } ?>

</select>

<br><br>

<label>Date</label>

<input
type="date"
name="attendance_date"
value="<?php echo $attendance["attendance_date"]; ?>"
required>

<br><br>

<label>Status</label>

<select name="status">

<option
<?php if($attendance["status"]=="Present") echo "selected"; ?>>
Present
</option>

<option
<?php if($attendance["status"]=="Absent") echo "selected"; ?>>
Absent
</option>

<option
<?php if($attendance["status"]=="Late") echo "selected"; ?>>
Late
</option>

<option
<?php if($attendance["status"]=="Leave") echo "selected"; ?>>
Leave
</option>

</select>

<br><br>

<label>Check In</label>

<input
type="time"
name="check_in"
value="<?php echo $attendance["check_in"]; ?>">

<br><br>

<label>Check Out</label>

<input
type="time"
name="check_out"
value="<?php echo $attendance["check_out"]; ?>">

<br><br>

<button type="submit">

Update Attendance

</button>

<a href="index.php" class="btn">

Cancel

</a>

</form>

</div>

</div>

</div>

</body>

</html>