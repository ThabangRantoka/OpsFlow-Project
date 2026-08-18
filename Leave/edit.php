<?php

require_once("../config/auth.php");
require_once("../config/permissions.php");
require_once("../config/DataBase.php");
requireRole(["Admin","Manager"]);

$id = intval($_GET["id"]);

$stmt = $conn->prepare("
SELECT *
FROM leave_requests
WHERE id=?
");

$stmt->bind_param("i",$id);
$stmt->execute();

$result=$stmt->get_result();

if($result->num_rows==0){

die("Leave request not found.");

}

$row=$result->fetch_assoc();

if($_SERVER["REQUEST_METHOD"]=="POST"){

$status=$_POST["status"] ?? "";
if(!in_array($status,["Pending","Approved","Rejected"],true)){header("Location:index.php?success=".urlencode("Invalid leave status."));exit();}

$stmt=$conn->prepare("
UPDATE leave_requests
SET status=?
WHERE id=?
");

$stmt->bind_param("si",$status,$id);

$stmt->execute();

header("Location:index.php");

exit();

}

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Edit Leave</title>

<link rel="stylesheet" href="../Assets/CSS/style.css">
<link rel="stylesheet" href="../Assets/CSS/dashboard.css">

</head>

<body>

<div class="dashboard">

<?php include("../Includes/sidebar.php"); ?>

<div class="main-content">

<?php include("../Includes/header.php"); ?>

<div class="recent">

<h2>Edit Leave Status</h2>

<form method="POST">

<label>Status</label>

<select name="status">

<option <?php if($row["status"]=="Pending") echo "selected"; ?>>
Pending
</option>

<option <?php if($row["status"]=="Approved") echo "selected"; ?>>
Approved
</option>

<option <?php if($row["status"]=="Rejected") echo "selected"; ?>>
Rejected
</option>

</select>

<br>

<button type="submit">

Save Changes

</button>

<a href="index.php" class="btn btn-primary">

Cancel

</a>

</form>

</div>

</div>

</div>

</body>

</html>