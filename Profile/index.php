<?php
/*
==========================================
OpsFlow Enterprise Management System
Profile/index.php
Developer: Moyahabo Thabang
Version : 3.1
==========================================
*/

require_once("../config/auth.php");

require_once("../config/DataBase.php");


// Logged-in user
$user_id = $_SESSION["user_id"];


// Get user information
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");

$stmt->bind_param("i", $user_id);

$stmt->execute();


$result = $stmt->get_result();


if ($result->num_rows == 0) {

    die("Error: User account not found.");

}


$user = $result->fetch_assoc();


$stmt->close();

?>

<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>My Profile</title>


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


<h2>My Profile</h2>


<br>


<form action="update.php" method="POST" enctype="multipart/form-data">


<label>Full Name</label>
<br>


<input
type="text"
name="fullname"
value="
<?php echo htmlspecialchars($user['fullname'] ?? '');
 
?>"
required>


<br>
<br>


<label>Email</label>
<br>


<input
type="email"
name="email"
value="
<?php echo htmlspecialchars($user['email'] ?? '');
 
?>"
required>


<br>
<br>


<label>Phone</label>
<br>


<input
type="text"
name="phone"
value="
<?php echo htmlspecialchars($user['phone'] ?? '');
 
?>">


<br>
<br>


<label>Username</label>
<br>


<input
type="text"
value="
<?php echo htmlspecialchars($user['username'] ?? '');
 
?>"
readonly>


<br>
<br>


<label>Role</label>
<br>


<input
type="text"
value="
<?php echo htmlspecialchars($user['role'] ?? '');
 
?>"
readonly>


<br>
<br>


<label>Profile Photo</label>
<br>


<input
type="file"
name="photo"
accept="image/*">


<br>
<br>


<?php
if (!empty($user['photo'])) {

?>

<img
src="photos/
<?php echo htmlspecialchars($user['photo']);
 
?>"
class="settings-logo"
style="max-width:150px;border-radius:10px;">


<br>
<br>


<?php
}

?>

<button type="submit" class="btn btn-primary">
💾 Update Profile
</button>


</form>


</div>


<hr>


<div class="recent">


<h2>Change Password</h2>


<br>


<form action="change_password.php" method="POST">


<label>Current Password</label>
<br>


<input
type="password"
name="current_password"
required>


<br>
<br>


<label>New Password</label>
<br>


<input
type="password"
name="new_password"
required>


<br>
<br>


<label>Confirm Password</label>
<br>


<input
type="password"
name="confirm_password"
required>


<br>
<br>


<button type="submit" class="btn btn-primary">
🔒 Change Password
</button>


</form>


</div>


</div>


</div>


</body>


</html>