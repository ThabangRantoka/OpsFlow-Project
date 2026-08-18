<?php

require_once("../config/auth.php");

require_once("../config/DataBase.php");

require_once("../config/permissions.php");


requireRole(["Admin","Manager"]);


$employees = $conn->query("
SELECT id, employee_number, fullname
FROM employees
ORDER BY fullname ASC
");


?>

<!DOCTYPE html>

<html>


<head>


<meta charset="UTF-8">


<title>Mark Attendance</title>


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


<h2>Mark Attendance</h2>


<?php if(isset($_GET["msg"])){
 
?>
<p style="background:#f8d7da;color:#842029;padding:12px 16px;border-radius:8px;margin:10px 0;">

<?php echo htmlspecialchars($_GET["msg"]);
 
?>
</p>

<?php }
 
?>


<br>


<form action="save.php" method="POST">


<label>Employee</label>


<select name="employee_id" required>


<option value="">Select Employee</option>


<?php

while($emp = $employees->fetch_assoc()){


?>

<option value="
<?php echo $emp["id"];
 
?>">


<?php

echo $emp["employee_number"];


echo " - ";


echo htmlspecialchars($emp["fullname"]);


?>

</option>


<?php

}


?>

</select>


<br>
<br>


<label>Date</label>


<input
type="date"
name="attendance_date"
required>


<br>
<br>


<label>Status</label>


<select name="status">


<option>Present</option>


<option>Absent</option>


<option>Late</option>


<option>Leave</option>


</select>


<br>
<br>


<label>Check In</label>


<input
type="time"
name="check_in">


<br>
<br>


<label>Check Out</label>


<input
type="time"
name="check_out">


<br>
<br>


<label>Remarks</label>


<textarea
name="remarks"
rows="4"
placeholder="Optional remarks">
</textarea>


<br>
<br>


<button type="submit">

Save Attendance

</button>


</form>


</div>


</div>


</div>


</body>


</html>