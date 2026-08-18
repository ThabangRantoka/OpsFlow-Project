<?php
require_once("../config/auth.php");
require_once("../config/permissions.php");
require_once("../config/DataBase.php");
require_once("../config/functions.php");

requireRole(["Employee"]);

if($_SERVER['REQUEST_METHOD']!=='POST'){
header('Location:index.php');
exit();
}

$leave_type=trim($_POST['leave_type']??'');
$start=trim($_POST['start_date']??'');
$end=trim($_POST['end_date']??'');
$reason=trim($_POST['reason']??'');

$allowed=['Annual','Sick','Maternity','Paternity','Study','Unpaid','Other'];

if(!in_array($leave_type,$allowed,true)||$start===''||$end===''||$reason===''||$start>$end){
header('Location:add.php?msg='.urlencode('Please complete the leave form correctly.'));
exit();
}

$stmt=$conn->prepare('SELECT id FROM employees WHERE email=? LIMIT 1');
$stmt->bind_param('s',$_SESSION['email']);
$stmt->execute();
$employee=$stmt->get_result()->fetch_assoc();
if(!$employee){
header('Location:index.php?success='.urlencode('Employee record not found.'));
exit();
}

$employee_id=(int)$employee['id'];

$stmt=$conn->prepare("SELECT id FROM leave_requests WHERE employee_id=? AND status='Pending' AND start_date<=? AND end_date>=? LIMIT 1");
$stmt->bind_param('iss',$employee_id,$end,$start);
$stmt->execute();
if($stmt->get_result()->num_rows){
header('Location:index.php?success='.urlencode('You already have an overlapping pending leave request.'));
exit();
}

$status='Pending';
$stmt=$conn->prepare('INSERT INTO leave_requests(employee_id,leave_type,start_date,end_date,reason,status) VALUES(?,?,?,?,?,?)');
$stmt->bind_param('isssss',$employee_id,$leave_type,$start,$end,$reason,$status);
if(!$stmt->execute()){
header('Location:add.php?error='.urlencode('Unable to save the leave request: '.$conn->error));
exit();
}

// Notify the requester and all active Admin/Manager users.
createNotification($conn,$_SESSION['user_id'],'Leave Request Submitted','Your '.$leave_type.' leave request was submitted and is pending review.','!');

$notify=$conn->query("SELECT id FROM users WHERE status='Active' AND role IN ('Admin','Manager')");
if($notify){
while($n=$notify->fetch_assoc()){
if((int)$n['id']!==(int)$_SESSION['user_id'])createNotification($conn,(int)$n['id'],'New Leave Request',$_SESSION['fullname'].' submitted a '.$leave_type.' leave request.','!');
}
}

logActivity($conn,$_SESSION['user_id'],$_SESSION['fullname'],'Submitted leave request');

header('Location:index.php?success='.urlencode('Request submitted successfully.'));
exit();

