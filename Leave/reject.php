<?php
require_once("../config/auth.php");
require_once("../config/permissions.php");
require_once("../config/DataBase.php");
require_once("../config/functions.php");

requireRole(["Admin","Manager"]);

$id=(int)($_GET['id']??0);
if($id<=0){
header('Location:index.php');
exit();
}

$stmt=$conn->prepare("UPDATE leave_requests SET status='Rejected' WHERE id=? AND status='Pending'");
$stmt->bind_param('i',$id);
$stmt->execute();

$stmt=$conn->prepare("SELECT e.fullname,e.email,u.id user_id FROM leave_requests lr INNER JOIN employees e ON e.id=lr.employee_id LEFT JOIN users u ON u.email=e.email WHERE lr.id=? LIMIT 1");
$stmt->bind_param('i',$id);
$stmt->execute();
$row=$stmt->get_result()->fetch_assoc();

if($row && !empty($row['user_id'])) createNotification($conn,(int)$row['user_id'],'Leave Rejected','Your leave request has been rejected by management.','×');

logActivity($conn,$_SESSION['user_id'],$_SESSION['fullname'],'Rejected leave request #'.$id);

header('Location:index.php?success='.urlencode('Leave request rejected.'));
exit();

