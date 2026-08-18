<?php
/*
==========================================
OpsFlow Enterprise Management System
Attendance Record Details
Developer: Moyahabo Thabang
Version: 1.0
==========================================
*/

require_once("../config/auth.php");

require_once("../config/DataBase.php");

require_once("../config/permissions.php");


// Allow Admin, Manager and Employee
requireRole(["Admin","Manager","Employee"]);


if(!isset($_GET["id"]) || !is_numeric($_GET["id"])){

    header("Location:index.php");

    exit();

}


$id = (int)$_GET["id"];


$stmt = $conn->prepare("
SELECT
attendance.*,
employees.employee_number,
employees.fullname,
employees.department,
employees.position
FROM attendance
INNER JOIN employees
ON attendance.employee_id = employees.id
WHERE attendance.id = ?
LIMIT 1
");


$stmt->bind_param("i", $id);

$stmt->execute();


$result = $stmt->get_result();


if($result->num_rows == 0){

    die("Attendance record not found.");

}


$attendance = $result->fetch_assoc();

?>

<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Attendance Details</title>


<link rel="stylesheet" href="../Assets/CSS/style.css">

<link rel="stylesheet" href="../Assets/CSS/dashboard.css">


</head>


<body>


<div class="dashboard">


<?php include("../Includes/sidebar.php");
 
?>

<div class="main-content">


<?php include("../Includes/header.php");
 
?>

<div class="recent">


<h2>📅 Attendance Details</h2>


<br>


<table>


<tr>

<th width="220">Employee Number</th>

<td>
<?php echo htmlspecialchars($attendance["employee_number"]);
 
?></td>

</tr>


<tr>

<th>Employee Name</th>

<td>
<?php echo htmlspecialchars($attendance["fullname"]);
 
?></td>

</tr>


<tr>

<th>Department</th>

<td>
<?php echo htmlspecialchars($attendance["department"]);
 
?></td>

</tr>


<tr>

<th>Position</th>

<td>
<?php echo htmlspecialchars($attendance["position"]);
 
?></td>

</tr>


<tr>

<th>Attendance Date</th>

<td>
<?php echo htmlspecialchars($attendance["attendance_date"]);
 
?></td>

</tr>


<tr>

<th>Status</th>

<td>


<?php

$status = $attendance["status"];


if($status=="Present"){


echo "<span class='badge active'>Present</span>";


}
elseif($status=="Absent"){


echo "<span class='badge inactive'>Absent</span>";


}
elseif($status=="Late"){


echo "<span class='badge leave'>Late</span>";


}
else{


echo htmlspecialchars($status);


}


?>

</td>


</tr>


<tr>

<th>Check In</th>

<td>
<?php echo htmlspecialchars($attendance["check_in"]);
 
?></td>

</tr>


<tr>

<th>Check Out</th>

<td>
<?php echo htmlspecialchars($attendance["check_out"]);
 
?></td>

</tr>


</table>


<br>
<br>


<?php if($_SESSION["role"]=="Admin" || $_SESSION["role"]=="Manager"){
 
?>

<a
href="edit.php?id=
<?php echo $attendance["id"];
 
?>"
class="btn btn-primary">

✏ Edit Attendance

</a>


<?php }
 
?>

<a
href="index.php"
class="btn btn-primary">

⬅ Back

</a>


</div>


</div>


</div>


</body>


</html>