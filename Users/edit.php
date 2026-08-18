<?php
/*
==========================================
OpsFlow Enterprise Management System
Users/edit.php
==========================================
*/

require_once("../config/auth.php");
require_once("../config/permissions.php");

requireRole("Admin");
require_once("../config/DataBase.php");

if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET["id"]);

$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    die("User not found.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>Edit User</title>

<link rel="stylesheet" href="../Assets/CSS/style.css">
<link rel="stylesheet" href="../Assets/CSS/dashboard.css">

</head>

<body>

<div class="dashboard">

<?php include("../Includes/sidebar.php"); ?>

<div class="main-content">

<?php include("../Includes/header.php"); ?>

<div class="recent">

<h2>Edit User</h2>

<br>

<form action="update.php" method="POST" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?php echo $user["id"]; ?>">

<label>Full Name</label>

<input
type="text"
name="fullname"
value="<?php echo htmlspecialchars($user["fullname"]); ?>"
required>

<br><br>

<label>Username</label>

<input
type="text"
name="username"
value="<?php echo htmlspecialchars($user["username"]); ?>"
required>

<br><br>

<label>Email</label>

<input
type="email"
name="email"
value="<?php echo htmlspecialchars($user["email"]); ?>"
required>

<br><br>

<label>Phone</label>

<input
type="text"
name="phone"
value="<?php echo htmlspecialchars($user["phone"]); ?>">

<br><br>

<label>Role</label>

<select name="role">

<?php
$roles = ["Admin","Manager","Employee"];

foreach($roles as $role){

$selected = ($user["role"] == $role) ? "selected" : "";

echo "<option value='$role' $selected>$role</option>";

}
?>

</select>

<br><br>

<label>Status</label>

<select name="status">

<option value="Active"
<?php if($user["status"]=="Active") echo "selected"; ?>>
Active
</option>

<option value="Inactive"
<?php if($user["status"]=="Inactive") echo "selected"; ?>>
Inactive
</option>

</select>

<br><br>

<label>Profile Photo</label>

<input type="file" name="photo">

<br><br>

<?php
if(!empty($user["photo"])){
?>

<img
src="photos/<?php echo htmlspecialchars($user["photo"]); ?>"
width="120"
style="border-radius:10px;">

<br><br>

<?php } ?>

<button type="submit">

💾 Update User

</button>

</form>

</div>

</div>

</div>

</body>

</html>