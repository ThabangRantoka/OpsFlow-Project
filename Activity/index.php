<?php
/*
==========================================
OpsFlow Enterprise Management System
Activity Logs
Developer: Moyahabo Thabang
Version: 1.0
==========================================
*/

require_once("../config/auth.php");

require_once("../config/permissions.php");


requireRole("Admin");

require_once("../config/DataBase.php");


$result = $conn->query("
SELECT *
FROM activity_logs
ORDER BY created_at DESC
");

?>

<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Activity Logs</title>


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


<h2>Activity Logs</h2>


<br>


<table>


<thead>


<tr>


<th>ID</th>


<th>User</th>


<th>Activity</th>


<th>Date & Time</th>


</tr>


</thead>


<tbody>


<?php
if($result->num_rows > 0){


while($row = $result->fetch_assoc()){

?>

<tr>


<td>
<?php echo $row["id"];
 
?></td>


<td>
<?php echo htmlspecialchars($row["fullname"]);
 
?></td>


<td>
<?php echo htmlspecialchars($row["action"]);
 
?></td>


<td>
<?php echo date("d M Y H:i", strtotime($row["created_at"]));
 
?></td>


</tr>


<?php
}


}
else{

?>

<tr>


<td colspan="4" style="text-align:center;">

No Activity Found

</td>


</tr>


<?php
}

?>

</tbody>


</table>


</div>


</div>


</div>


</body>


</html>