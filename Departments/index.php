<?php
require_once("../config/auth.php");
require_once("../config/DataBase.php");
require_once("../config/permissions.php");
requireRole(["Admin","Manager","Employee"]);

$departments=$conn->query("SELECT d.*, COUNT(e.id) staff_count, COALESCE(SUM(CASE WHEN e.status='Active' THEN e.salary ELSE 0 END),0) payroll FROM departments d LEFT JOIN employees e ON e.department=d.department_name OR e.department=d.department_code GROUP BY d.id ORDER BY d.department_name ASC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>OpsFlow | Departments</title>
<link rel="stylesheet" href="../Assets/CSS/style.css">
<link rel="stylesheet" href="../Assets/CSS/dashboard.css">
</head>

<body>
<div class="dashboard">
<?php include("../Includes/sidebar.php");
 
?><main class="main-content">
<?php include("../Includes/header.php");
 
?>
<section class="page-heading">
<div class="page-toolbar">
<div>
<h1>Departments</h1>
<p>Organisational structure, headcount and payroll cost</p>
</div>
<?php if(in_array($_SESSION['role'],['Admin','Manager'],true)){
 
?><a href="add.php" class="btn btn-primary">
<i class="fa-solid fa-plus">
</i> New department</a>
<?php }
 
?></div>
</section>

<div class="dept-grid">

<?php if($departments && $departments->num_rows){
while($d=$departments->fetch_assoc()){
 
?><article class="dept-card">
<div class="dept-card-top">
<div class="dept-icon">
<i class="fa-solid fa-building">
</i>
</div>
<div class="dept-actions">
<?php if(in_array($_SESSION['role'],['Admin','Manager'],true)){
 
?><a href="edit.php?id=
<?php echo (int)$d['id'];
 
?>" class="edit" title="Edit">
<i class="fa-solid fa-pen">
</i>
</a>
<?php }
 
?>
<?php if($_SESSION['role']==='Admin'){
 
?><a href="delete.php?id=
<?php echo (int)$d['id'];
 
?>" class="delete" title="Delete" onclick="return confirm('Delete this department?')">
<i class="fa-regular fa-trash-can">
</i>
</a>
<?php }
 
?></div>
</div>
<div class="dept-name">
<?php echo htmlspecialchars($d['department_name']);
 
?></div>
<div class="dept-code">
<?php echo htmlspecialchars($d['department_code']);
 
?></div>
<div class="dept-description">
<?php echo htmlspecialchars($d['description'] ?: 'No description provided');
 
?></div>
<div class="dept-meta">
<span>
<i class="fa-solid fa-users">
</i>
 
<?php echo (int)$d['staff_count'];
 
?> staff</span>
<span class="dept-payroll">R 
<?php echo number_format((float)$d['payroll'],0,'.',' ');
 
?></span>
</div>
</article>
<?php }
}
else{
 
?><div class="content-card">
<div class="empty-state">
<strong>No departments found</strong>Add a department to build your organisational structure.</div>
</div>
<?php }
 
?></div>

</main>
</div>
</body>
</html>
