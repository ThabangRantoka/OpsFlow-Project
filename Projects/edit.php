<?php
/**
 * ==========================================================
 * OpsFlow Enterprise Management System
 * File: Projects/edit.php
 * ==========================================================
 */

require_once("../config/auth.php");
require_once("../config/DataBase.php");
require_once("../config/permissions.php");

requireRole(["Admin","Manager"]);

if(!isset($_GET["id"]) || !is_numeric($_GET["id"])){
    header("Location:index.php");
    exit();
}

$id = (int)$_GET["id"];

/* Get Project */
$stmt = $conn->prepare("
SELECT *
FROM projects
WHERE id=?
");

$stmt->bind_param("i",$id);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows==0){
    die("Project not found.");
}

$project = $result->fetch_assoc();

/* Load Departments */
$departments = $conn->query("
SELECT department_name
FROM departments
ORDER BY department_name ASC
");
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Project</title>

<link rel="stylesheet" href="../Assets/CSS/style.css">
<link rel="stylesheet" href="../Assets/CSS/dashboard.css">

</head>

<body>

<div class="dashboard">

<?php include("../Includes/sidebar.php"); ?>

<div class="main-content">

<?php include("../Includes/header.php"); ?>

<div class="recent">

<h2>Edit Project</h2>

<br>

<form action="update.php" method="POST">

<input
type="hidden"
name="id"
value="<?php echo $project["id"]; ?>">

<input
type="text"
name="project_code"
value="<?php echo htmlspecialchars($project["project_code"]); ?>"
required>

<br><br>

<input
type="text"
name="project_name"
value="<?php echo htmlspecialchars($project["project_name"]); ?>"
required>

<br><br>

<select name="department" required>

<?php while($dept = $departments->fetch_assoc()){ ?>

<option
value="<?php echo htmlspecialchars($dept["department_name"]); ?>"
<?php if($dept["department_name"]==$project["department"]) echo "selected"; ?>>

<?php echo htmlspecialchars($dept["department_name"]); ?>

</option>

<?php } ?>

</select>

<br><br>

<input
type="text"
name="project_manager"
value="<?php echo htmlspecialchars($project["project_manager"]); ?>">

<br><br>

<label>Start Date</label>

<input
type="date"
name="start_date"
value="<?php echo $project["start_date"]; ?>">

<br><br>

<label>End Date</label>

<input
type="date"
name="end_date"
value="<?php echo $project["end_date"]; ?>">

<br><br>

<label>Budget</label>

<input
type="number"
step="0.01"
name="budget"
value="<?php echo $project["budget"]; ?>"
required>

<br><br>

<label>Progress (%)</label>

<input
type="number"
name="progress"
min="0"
max="100"
value="<?php echo $project["progress"]; ?>">

<br><br>

<label>Priority</label>

<select name="priority">

<option <?php if($project["priority"]=="Low") echo "selected"; ?>>Low</option>

<option <?php if($project["priority"]=="Medium") echo "selected"; ?>>Medium</option>

<option <?php if($project["priority"]=="High") echo "selected"; ?>>High</option>

<option <?php if($project["priority"]=="Critical") echo "selected"; ?>>Critical</option>

</select>

<br><br>

<label>Status</label>

<select name="status">

<option <?php if($project["status"]=="Planning") echo "selected"; ?>>Planning</option>

<option <?php if($project["status"]=="In Progress") echo "selected"; ?>>In Progress</option>

<option <?php if($project["status"]=="Completed") echo "selected"; ?>>Completed</option>

<option <?php if($project["status"]=="On Hold") echo "selected"; ?>>On Hold</option>

</select>

<br><br>

<button type="submit">

Update Project

</button>

</form>

</div>

</div>

</div>

</body>

</html>