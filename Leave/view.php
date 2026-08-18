<?php

require_once("../config/auth.php");

require_once("../config/permissions.php");

require_once("../config/DataBase.php");

$role=$_SESSION["role"]??"Employee";

requireRole(["Admin","Manager","Employee"]);


$id = intval($_GET["id"]);


$sql = "
SELECT lr.*, e.employee_number, e.fullname
FROM leave_requests lr
INNER JOIN employees e
ON lr.employee_id = e.id
WHERE lr.id = ?
";


if ($role === "Employee") {

    $sql .= " AND e.email = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("is", $id, $_SESSION["email"]);

}
 else {

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $id);

}

$stmt->execute();


$result = $stmt->get_result();


if($result->num_rows==0){

    die("Leave request not found.");

}


$row = $result->fetch_assoc();


?>

<!DOCTYPE html>

<html>

<head>


<meta charset="UTF-8">


<title>View Leave</title>


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


<h2>Leave Details</h2>


<table>


<tr>

<th>Employee</th>

<td>
<?php echo htmlspecialchars($row["employee_number"]);
 
?> -
<?php echo htmlspecialchars($row["fullname"]);
 
?></td>

</tr>


<tr>

<th>Leave Type</th>

<td>
<?php echo htmlspecialchars($row["leave_type"]);
 
?></td>

</tr>


<tr>

<th>Start Date</th>

<td>
<?php echo $row["start_date"];
 
?></td>

</tr>


<tr>

<th>End Date</th>

<td>
<?php echo $row["end_date"];
 
?></td>

</tr>


<tr>

<th>Status</th>

<td>
<?php echo htmlspecialchars($row["status"]);
 
?></td>

</tr>


<tr>

<th>Reason</th>

<td>
<?php echo nl2br(htmlspecialchars($row["reason"]));
 
?></td>

</tr>


</table>


<br>


<a href="index.php" class="btn btn-primary">
⬅ Back
</a>


</div>


</div>


</div>


</body>


</html>