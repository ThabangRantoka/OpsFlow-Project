<?php
require_once("../config/auth.php");
require_once("../config/permissions.php");
requireRole(["Admin","Manager"]);
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>Add Department</title>

<link rel="stylesheet" href="../Assets/CSS/style.css">
<link rel="stylesheet" href="../Assets/CSS/dashboard.css">

</head>

<body>

<div class="dashboard">

<?php include("../Includes/sidebar.php"); ?>

<div class="main-content">

<?php include("../Includes/header.php"); ?>

<div class="recent">

<h2>Add Department</h2>

<br>

<form action="save.php" method="POST">

<input
type="text"
name="department_name"
placeholder="Department Name"
required>

<br><br>

<input
type="text"
name="department_code"
placeholder="Department Code (IT, HR, FIN...)"
required>

<br><br>

<textarea
name="description"
placeholder="Department Description"
rows="5"></textarea>

<br><br>

<button class="btn btn-primary">

Save Department

</button>

</form>

</div>

</div>

</div>

</body>

</html>