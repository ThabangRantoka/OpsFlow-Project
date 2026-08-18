<?php
session_start();

require_once("../config/DataBase.php");
require_once("../config/functions.php");

if($_SERVER['REQUEST_METHOD']!=='POST'){
header('Location:login.php');
exit();
}

$email=trim($_POST['email']??'');
$password=$_POST['password']??'';
$requestedRole=trim($_POST['role']??'');

function loginFail($msg,$email=''){
header('Location:login.php?msg='.urlencode($msg).'&email='.urlencode($email));
exit();
}

if($email===''||$password==='')loginFail('Please enter your email and password.',$email);

$stmt=$conn->prepare('SELECT * FROM users WHERE email=? LIMIT 1');
if(!$stmt)loginFail('Database error.',$email);
$stmt->bind_param('s',$email);
$stmt->execute();
$result=$stmt->get_result();
if($result->num_rows!==1)loginFail('Email not found.',$email);
$user=$result->fetch_assoc();

if($requestedRole!==''&&!in_array($requestedRole,['Admin','Manager','Employee'],true))loginFail('Invalid role selection.',$email);
if($requestedRole!==''&&$requestedRole!==($user['role']??''))loginFail('Selected role does not match this account.',$email);
if(($user['status']??'Active')!=='Active')loginFail('Your account is deactivated. Contact the administrator.',$email);
if(!password_verify($password,$user['password']))loginFail('Invalid password.',$email);

session_regenerate_id(true);
$_SESSION['user_id']=(int)$user['id'];
$_SESSION['fullname']=$user['fullname'];
$_SESSION['email']=$user['email'];
$_SESSION['role']=$user['role'];
$_SESSION['employee_id']=$user['employee_id']??null;

logActivity($conn,$_SESSION['user_id'],$_SESSION['fullname'],'Logged into the system as '.$user['role']);
header('Location:../Dashboard/index.php');
exit();

