<?php

require_once("../config/auth.php");
require_once("../config/DataBase.php");

// Check ID
if(!isset($_GET["id"])){

    header("Location:index.php");
    exit();

}

$id = $_GET["id"];

// Get Employee
$sql = "SELECT * FROM employees WHERE id=?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i",$id);

$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows==0){

    die("Employee not found.");

}

$employee = $result->fetch_assoc();

?>

<!DOCTYPE html>

<html>

<head>

<title>Edit Employee</title>

<link rel="stylesheet" href="../Assets/CSS/style.css">

<link rel="stylesheet" href="../Assets/CSS/dashboard.css">

</head>

<body>

<div class="dashboard">

<?php include("../Includes/sidebar.php"); ?>

<div class="main-content">

<?php include("../Includes/header.php"); ?>

<div class="recent">

<h2>Edit Employee</h2>

<br>

<form action="update.php" method="POST">

<input
type="hidden"
name="id"
value="<?php echo $employee["id"]; ?>">

<input
type="text"
name="employee_number"
value="<?php echo htmlspecialchars($employee["employee_number"]); ?>"
required>

<br><br>

<input
type="text"
name="fullname"
value="<?php echo htmlspecialchars($employee["fullname"]); ?>"
required>

<br><br>

<select name="gender">

<option
<?php if($employee["gender"]=="Male") echo "selected"; ?>>
Male
</option>

<option
<?php if($employee["gender"]=="Female") echo "selected"; ?>>
Female
</option>

</select>

<br><br>

<input
type="email"
name="email"
value="<?php echo htmlspecialchars($employee["email"]); ?>"
required>

<br><br>

<input
type="text"
name="phone"
value="<?php echo htmlspecialchars($employee["phone"]); ?>">

<br><br>

<input
type="text"
name="department"
value="<?php echo htmlspecialchars($employee["department"]); ?>">

<br><br>

<input
type="text"
name="position"
value="<?php echo htmlspecialchars($employee["position"]); ?>">

<br><br>

<input
type="number"
step="0.01"
name="salary"
value="<?php echo $employee["salary"]; ?>">

<br><br>

<input
type="date"
name="hire_date"
value="<?php echo $employee["hire_date"]; ?>">

<br><br>

<select name="status">

<option
<?php if($employee["status"]=="Active") echo "selected"; ?>>
Active
</option>

<option
<?php if($employee["status"]=="Inactive") echo "selected"; ?>>
Inactive
</option>

<option
<?php if($employee["status"]=="Leave") echo "selected"; ?>>
Leave
</option>

</select>

<br><br>

<button>

Update Employee

</button>

</form>

</div>

</div>

</div>

</body>

</html>