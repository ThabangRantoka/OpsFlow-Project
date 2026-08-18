<?php
/**
 * ==========================================================
 * OpsFlow Enterprise Management System
 * File: Departments/edit.php
 * Description: Edit Department
 * Developer: Moyahabo Thabang
 * ==========================================================
 */

require_once("../config/auth.php");
require_once("../config/DataBase.php");

require_once("../config/permissions.php");

// Only Admin and Manager
requireRole(["Admin","Manager"]);   



if(!isset($_GET["id"])){
    header("Location:index.php");
    exit();
}

$id = intval($_GET["id"]);

$stmt = $conn->prepare("SELECT * FROM departments WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows==0){
    die("Department not found.");
}

$department = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>

<head>

<title>Edit Department</title>

<link rel="stylesheet" href="../Assets/CSS/style.css">
<link rel="stylesheet" href="../Assets/CSS/dashboard.css">

</head>

<body>

<div class="dashboard">

<?php include("../Includes/sidebar.php"); ?>

<div class="main-content">

<?php include("../Includes/header.php"); ?>

<div class="recent">

<h2>Edit Department</h2>

<br>

<form action="update.php" method="POST">

<input
type="hidden"
name="id"
value="<?php echo $department["id"]; ?>">

<input
type="text"
name="department_name"
value="<?php echo htmlspecialchars($department["department_name"]); ?>"
required>

<br><br>

<input
type="text"
name="department_code"
value="<?php echo htmlspecialchars($department["department_code"]); ?>"
required>

<br><br>

<textarea
name="description"
rows="5"><?php echo htmlspecialchars($department["description"]); ?></textarea>

<br><br>

<button class="btn btn-primary">

Update Department

</button>

</form>

</div>

</div>

</div>

</body>

</html>