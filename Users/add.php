<?php
/*
==========================================
OpsFlow Enterprise Management System
Users/add.php
Developer: Moyahabo Thabang
Version: 3.0
==========================================
*/

require_once("../config/auth.php");

require_once("../config/permissions.php");


requireRole("Admin");

require_once("../config/DataBase.php");

?>

<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Add User</title>


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


<h2>Add New User</h2>


<br>


<form action="insert.php" method="POST" enctype="multipart/form-data">


<label>Full Name</label>


<input
type="text"
name="fullname"
required>


<br>
<br>


<label>Username</label>


<input
type="text"
name="username"
required>


<br>
<br>


<label>Email</label>


<input
type="email"
name="email"
required>


<br>
<br>


<label>Phone</label>


<input
type="text"
name="phone">


<br>
<br>


<label>Password</label>


<input
type="password"
name="password"
required>


<br>
<br>


<label>Confirm Password</label>


<input
type="password"
name="confirm_password"
required>


<br>
<br>


<label>Role</label>


<select name="role" required>


<option value="">Select Role</option>


<option>Admin</option>


<option>Manager</option>



<option>Employee</option>


</select>


<br>
<br>


<label>Status</label>


<select name="status">


<option selected>Active</option>


<option>Inactive</option>


</select>


<br>
<br>


<label>Profile Photo</label>


<input
type="file"
name="photo"
accept="image/*">


<br>
<br>


<button type="submit">

➕ Create User

</button>


</form>


</div>


</div>


</div>


</body>


</html>