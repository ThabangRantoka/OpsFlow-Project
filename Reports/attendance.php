<?php
require_once("../config/auth.php");
require_once("../config/permissions.php");
require_once("../config/DataBase.php");
requireRole(["Admin","Manager"]);

$result=$conn->query('SELECT e.employee_number,e.fullname,a.attendance_date,a.status,a.check_in,a.check_out FROM attendance a INNER JOIN employees e ON e.id=a.employee_id ORDER BY a.attendance_date DESC');

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>OpsFlow | Attendance Report</title>
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
<h1>Attendance Report</h1>
<p>Operational report generated from the current workspace data.</p>
</div>
<div class="actions">
<button class="btn btn-light" onclick="window.print()">
<i class="fa-solid fa-print">
</i> Print</button>
<a class="btn btn-primary" href="exports/export_attendance.php">
<i class="fa-solid fa-download">
</i> Export CSV</a>
</div>
</div>
</section>

<div class="content-card flush">
<table>
<thead>
<tr>

<th>Employee No</th>

<th>Employee</th>

<th>Date</th>

<th>Status</th>

<th>Check In</th>

<th>Check Out</th>

</tr>
</thead>
<tbody>
<?php if($result && $result->num_rows){
while($r=$result->fetch_assoc()){
 
?><tr>

<td>
<?php echo htmlspecialchars((string)($r['employee_number'] ?? '—'));
 
?></td>

<td>
<?php echo htmlspecialchars((string)($r['fullname'] ?? '—'));
 
?></td>

<td>
<?php echo htmlspecialchars((string)($r['attendance_date'] ?? '—'));
 
?></td>

<td>
<span class="badge 
<?php echo $r['status']==='Completed'||$r['status']==='Present'||$r['status']==='Active'?'active':($r['status']==='Rejected'||$r['status']==='Absent'||$r['status']==='Inactive'?'inactive':'leave');
 
?>">
<?php echo htmlspecialchars($r['status']);
 
?></span>
</td>

<td>
<?php echo htmlspecialchars((string)($r['check_in'] ?? '—'));
 
?></td>

<td>
<?php echo htmlspecialchars((string)($r['check_out'] ?? '—'));
 
?></td>

</tr>
<?php }
}
else{
 
?><tr>
<td colspan="6">
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