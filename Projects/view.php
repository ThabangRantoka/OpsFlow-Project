<?php

require_once("../config/auth.php");
require_once("../config/DataBase.php");

require_once("../config/permissions.php");

// Everyone can view project details
requireRole(["Admin","Manager","Employee"]);



if(!isset($_GET["id"]) || !is_numeric($_GET["id"])){

header("Location:index.php");
exit();

}

$id=(int)$_GET["id"];

$stmt=$conn->prepare("

SELECT *

FROM projects

WHERE id=?

LIMIT 1

");

$stmt->bind_param("i",$id);

$stmt->execute();

$result=$stmt->get_result();

if($result->num_rows==0){

die("Project not found.");

}

$project=$result->fetch_assoc();

?>

<!DOCTYPE html>

<html>

<head>

<title>Project Details</title>

<link rel="stylesheet" href="../Assets/CSS/style.css">
<link rel="stylesheet" href="../Assets/CSS/dashboard.css">

</head>

<body>

<div class="dashboard">

<?php include("../Includes/sidebar.php"); ?>

<div class="main-content">

<?php include("../Includes/header.php"); ?>

<div class="recent">

<h2>📁 Project Details</h2>

<table>

<tr>
<th width="250">Project Code</th>
<td><?= htmlspecialchars($project["project_code"]); ?></td>
</tr>

<tr>
<th>Project Name</th>
<td><?= htmlspecialchars($project["project_name"]); ?></td>
</tr>

<tr>
<th>Department</th>
<td><?= htmlspecialchars($project["department"]); ?></td>
</tr>

<tr>
<th>Project Manager</th>
<td><?= htmlspecialchars($project["project_manager"]); ?></td>
</tr>

<tr>
<th>Start Date</th>
<td><?= htmlspecialchars($project["start_date"]); ?></td>
</tr>

<tr>
<th>End Date</th>
<td><?= htmlspecialchars($project["end_date"]); ?></td>
</tr>

<tr>
<th>Budget</th>
<td>M <?= number_format($project["budget"],2); ?></td>
</tr>

<tr>
<th>Progress</th>
<td>

<div style="width:250px;background:#e9ecef;border-radius:20px;overflow:hidden;">

<div
style="
width:<?= $project["progress"]; ?>%;
background:#28a745;
color:white;
padding:5px;
text-align:center;">

<?= $project["progress"]; ?>%

</div>

</div>

</td>
</tr>

<tr>
<th>Priority</th>
<td>

<?php

switch($project["priority"]){

case "Critical":
echo "<span style='color:red;font-weight:bold;'>🔴 Critical</span>";
break;

case "High":
echo "<span style='color:orange;font-weight:bold;'>🟠 High</span>";
break;

case "Medium":
echo "<span style='color:#0d6efd;font-weight:bold;'>🔵 Medium</span>";
break;

default:
echo "<span style='color:green;font-weight:bold;'>🟢 Low</span>";

}

?>

</td>

</tr>

<tr>

<th>Status</th>

<td>

<?php

switch($project["status"]){

case "Completed":

echo "<span class='badge active'>Completed</span>";

break;

case "In Progress":

echo "<span class='badge leave'>In Progress</span>";

break;

case "Planning":

echo "<span class='badge inactive'>Planning</span>";

break;

default:

echo "<span class='badge inactive'>On Hold</span>";

}

?>

</td>

</tr>

<tr>

<th>Created</th>

<td>

<?= htmlspecialchars($project["created_at"]); ?>

</td>

</tr>

</table>

<br>
<?php if($_SESSION["role"]=="Admin" || $_SESSION["role"]=="Manager"){ ?>

<a
href="edit.php?id=<?= $project["id"]; ?>"
class="btn btn-primary">

✏ Edit Project

</a>

<?php } ?>

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