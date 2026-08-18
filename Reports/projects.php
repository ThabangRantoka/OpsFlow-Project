<?php
require_once("../config/auth.php");
require_once("../config/permissions.php");
require_once("../config/DataBase.php");
requireRole(["Admin","Manager"]);

$result=$conn->query('SELECT project_code,project_name,department,project_manager,budget,progress,priority,status FROM projects ORDER BY project_name ASC');

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>OpsFlow | Project Report</title>
<link rel="stylesheet" href="../Assets/CSS/style.css">
<link rel="stylesheet" href="../Assets/CSS/dashboard.css">
<style>@media print{.sidebar,.topbar,.btn{display:none!important}.main-content{margin:0;padding:0}.content-card{box-shadow:none;border:0}}</style>
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
<h1>Project Report</h1>
<p>Operational report generated from the current workspace data.</p>
</div>
<div class="actions">
<button class="btn btn-light" onclick="window.print()">
<i class="fa-solid fa-print">
</i> Print</button>
<a class="btn btn-primary" href="exports/export_projects.php">
<i class="fa-solid fa-download">
</i> Export CSV</a>
</div>
</div>
</section>

<div class="content-card flush">
<table>
<thead>
<tr>

<th>Code</th>

<th>Project</th>

<th>Department</th>

<th>Manager</th>

<th>Budget</th>

<th>Progress</th>

<th>Priority</th>

<th>Status</th>

</tr>
</thead>
<tbody>
<?php if($result && $result->num_rows){
while($r=$result->fetch_assoc()){
 
?><tr>

<td>
<?php echo htmlspecialchars((string)($r['project_code'] ?? '—'));
 
?></td>

<td>
<?php echo htmlspecialchars((string)($r['project_name'] ?? '—'));
 
?></td>

<td>
<?php echo htmlspecialchars((string)($r['department'] ?? '—'));
 
?></td>

<td>
<?php echo htmlspecialchars((string)($r['project_manager'] ?? '—'));
 
?></td>

<td>R 
<?php echo number_format((float)$r['budget'],0,'.',' ');
 
?></td>

<td>
<div class="progress-track">
<div class="progress-fill" style="width:
<?php echo (int)$r['progress'];
 
?>%">
</div>
</div>
<span class="small muted">
<?php echo (int)$r['progress'];
 
?>%</span>
</td>

<td>
<?php echo htmlspecialchars((string)($r['priority'] ?? '—'));
 
?></td>

<td>
<span class="badge 
<?php echo $r['status']==='Completed'||$r['status']==='Present'||$r['status']==='Active'?'active':($r['status']==='Rejected'||$r['status']==='Absent'||$r['status']==='Inactive'?'inactive':'leave');
 
?>">
<?php echo htmlspecialchars($r['status']);
 
?></span>
</td>

</tr>
<?php }
}
else{
 
?><tr>
<td colspan="8">
<div class="empty-state">
<strong>No records found</strong>There is no data for this report yet.</div>
</td>
</tr>
<?php }
 
?></tbody>
</table>
</div>
</main>
</div>
</body>
</html>