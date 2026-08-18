<?php

require_once("../config/auth.php");
require_once("../config/permissions.php");

requireRole("Admin");
require_once("../config/DataBase.php");

if(!isset($_GET["id"]) || !is_numeric($_GET["id"])){

header("Location:index.php");
exit();

}

$id=(int)$_GET["id"];

$stmt=$conn->prepare("

SELECT *

FROM users

WHERE id=?

LIMIT 1

");

$stmt->bind_param("i",$id);

$stmt->execute();

$result=$stmt->get_result();

if($result->num_rows==0){

die("User not found.");

}

$user=$result->fetch_assoc();

?>

<!DOCTYPE html>

<html>

<head>

<title>User Details</title>

<link rel="stylesheet" href="../Assets/CSS/style.css">
<link rel="stylesheet" href="../Assets/CSS/dashboard.css">

</head>

<body>

<div class="dashboard">

<?php include("../Includes/sidebar.php"); ?>

<div class="main-content">

<?php include("../Includes/header.php"); ?>

<div class="recent">

<h2>👤 User Profile</h2>

<table>

<tr>

<th width="250">Full Name</th>

<td><?= htmlspecialchars($user["fullname"]); ?></td>

</tr>

<tr>

<th>Username</th>

<td><?= htmlspecialchars($user["username"]); ?></td>

</tr>

<tr>

<th>Email</th>

<td><?= htmlspecialchars($user["email"]); ?></td>

</tr>

<tr>

<th>Phone</th>

<td><?= htmlspecialchars($user["phone"]); ?></td>

</tr>

<tr>

<th>Role</th>

<td><?= htmlspecialchars($user["role"]); ?></td>

</tr>

<tr>

<th>Status</th>

<td><?= htmlspecialchars($user["status"]); ?></td>

</tr>

<tr>

<th>Created</th>

<td><?= htmlspecialchars($user["created_at"]); ?></td>

</tr>

</table>

<br>

<a href="edit.php?id=<?= $user["id"]; ?>" class="btn btn-primary">

✏ Edit User

</a>

<a href="index.php" class="btn">

⬅ Back

</a>

</div>

</div>

</div>

</body>

</html>