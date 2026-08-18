<?php
require_once("../config/auth.php");
require_once("../config/permissions.php");
require_once("../config/DataBase.php");

requireRole(["Admin","Manager","Employee"]);

$role=$_SESSION['role']??'Employee';
$requests=[];
$error='';

if($role==='Employee'){

    $stmt=$conn->prepare("SELECT lr.id,lr.leave_type,lr.start_date,lr.end_date,lr.reason,lr.status,lr.created_at,e.employee_number,e.fullname FROM leave_requests lr INNER JOIN employees e ON e.id=lr.employee_id WHERE e.email=? ORDER BY lr.created_at DESC,lr.id DESC");

    $email=$_SESSION['email']??'';
$stmt->bind_param('s',$email);
$stmt->execute();
$result=$stmt->get_result();

}
else{
$result=$conn->query("SELECT lr.id,lr.leave_type,lr.start_date,lr.end_date,lr.reason,lr.status,lr.created_at,e.employee_number,e.fullname FROM leave_requests lr INNER JOIN employees e ON e.id=lr.employee_id ORDER BY lr.created_at DESC,lr.id DESC");
}

if($result){
while($row=$result->fetch_assoc())$requests[]=$row;
}
else{
$error='Unable to load leave requests: '.$conn->error;
}

$pendingCount=0;
foreach($requests as $r)if($r['status']==='Pending')$pendingCount++;

function leaveStatusClass($s){
return $s==='Approved'?'approved':($s==='Rejected'?'rejected':'pending');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>OpsFlow | Leave</title>
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
<h1>Leave</h1>
<p>
<?php echo $pendingCount;
 
?> request
<?php echo $pendingCount===1?'':'s';
 
?> awaiting a decision</p>
</div>
<?php if($role==='Employee'){
 
?><a href="add.php" class="btn btn-primary">
<i class="fa-solid fa-plus">
</i> New request</a>
<?php }
 
?></div>
</section>

<?php if(isset($_GET['success'])){
 
?><div class="alert alert-success">
<?php echo htmlspecialchars($_GET['success']);
 
?></div>
<?php }
 
?>
<?php if($error){
 
?><div class="alert alert-error">
<?php echo htmlspecialchars($error);
 
?></div>
<?php }
 
?>
<div class="leave-card">
<table>
<thead>
<tr>
<th>Employee</th>
<th>Type</th>
<th>Period</th>
<th>Reason</th>
<th>Status</th>
<th>Actions</th>
</tr>
</thead>
<tbody>

<?php if(!$requests){
 
?><tr>
<td colspan="6">
<div class="empty-state">
<strong>No leave requests yet</strong>
<?php echo $role==='Employee'?'Submit a leave request and it will appear here immediately.':'Leave requests submitted by employees will appear here.';
 
?></div>
</td>
</tr>
<?php }
 else {
 foreach($requests as $r){
 
?><tr>
<td>
<span style="font-weight:600;color:#17263a">
<?php echo htmlspecialchars($r['fullname']);
 
?></span>
<span class="employee-meta">
<?php echo htmlspecialchars($r['employee_number']);
 
?></span>
</td>
<td>
<?php echo htmlspecialchars($r['leave_type']);
 
?></td>
<td>
<?php echo date('d M Y',strtotime($r['start_date']));
 
?> → 
<?php echo date('d M Y',strtotime($r['end_date']));
 
?></td>
<td>
<span class="reason" title="
<?php echo htmlspecialchars($r['reason']??'');
 
?>">
<?php echo htmlspecialchars($r['reason']?:'—');
 
?></span>
</td>
<td>
<span class="leave-status 
<?php echo leaveStatusClass($r['status']);
 
?>">
<?php echo htmlspecialchars($r['status']);
 
?></span>
</td>
<td>
<div class="leave-actions">
<a href="view.php?id=
<?php echo (int)$r['id'];
 
?>" class="view" title="View">
<i class="fa-regular fa-eye">
</i>
</a>
<?php if(in_array($role,['Admin','Manager'],true)&&$r['status']==='Pending'){
 
?><a href="approve.php?id=
<?php echo (int)$r['id'];
 
?>" class="edit" title="Approve" onclick="return confirm('Approve this leave request?')">
<i class="fa-solid fa-check">
</i>
</a>
<a href="reject.php?id=
<?php echo (int)$r['id'];
 
?>" class="delete" title="Reject" onclick="return confirm('Reject this leave request?')">
<i class="fa-solid fa-xmark">
</i>
</a>
<?php }
 
?>
<?php if($role==='Admin'){
 
?><a href="delete.php?id=
<?php echo (int)$r['id'];
 
?>" class="delete" title="Delete" onclick="return confirm('Delete this leave request?')">
<i class="fa-regular fa-trash-can">
</i>
</a>
<?php }
 
?></div>
</td>
</tr>
<?php }
}
 
?></tbody>
</table>
</div>

</main>
</div>
</body>
</html>
