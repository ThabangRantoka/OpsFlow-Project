<?php
require_once("../config/auth.php");
require_once("../config/permissions.php");
require_once("../config/DataBase.php");
require_once("../config/functions.php");
requireRole("Admin");

if($_SERVER['REQUEST_METHOD']!=='POST'){
header('Location:index.php');
exit();
}

$id=(int)($_POST['id']??0);
$fullname=trim($_POST['fullname']??'');
$username=trim($_POST['username']??'');
$email=trim($_POST['email']??'');
$phone=trim($_POST['phone']??'');
$role=$_POST['role']??'';
$status=$_POST['status']??'';

if($id<=0||$fullname===''||$username===''||!filter_var($email,FILTER_VALIDATE_EMAIL)||!in_array($role,['Admin','Manager','Employee'],true)||!in_array($status,['Active','Inactive'],true)){
header('Location:index.php?msg='.urlencode('Invalid user details.'));
exit();
}

if($id===(int)$_SESSION['user_id']&&($role!=='Admin'||$status!=='Active')){
header('Location:index.php?msg='.urlencode('You cannot remove or deactivate your own administrator account.'));
exit();
}

$check=$conn->prepare('SELECT id FROM users WHERE email=? AND id<>? LIMIT 1');
$check->bind_param('si',$email,$id);
$check->execute();
if($check->get_result()->num_rows){
header('Location:edit.php?id='.$id.'&msg='.urlencode('Email already belongs to another user.'));
exit();
}

$stmt=$conn->prepare('UPDATE users SET fullname=?,username=?,email=?,phone=?,role=?,status=? WHERE id=?');
$stmt->bind_param('ssssssi',$fullname,$username,$email,$phone,$role,$status,$id);

if($stmt->execute()){
logActivity($conn,$_SESSION['user_id'],$_SESSION['fullname'],'Updated user #'.$id);
header('Location:index.php?success='.urlencode('User updated successfully.'));
exit();
}

header('Location:edit.php?id='.$id.'&msg='.urlencode('Unable to update user.'));
exit();

