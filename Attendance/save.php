<?php
require_once("../config/auth.php");require_once("../config/DataBase.php");require_once("../config/permissions.php");require_once("../config/functions.php");
requireRole(["Admin","Manager"]);
if($_SERVER['REQUEST_METHOD']!=='POST'){header('Location:index.php');exit();}
$employee_id=(int)($_POST['employee_id']??0);$date=trim($_POST['attendance_date']??'');$status=trim($_POST['status']??'');
$allowed=['Present','Absent','Late','Leave'];
if($employee_id<=0||!preg_match('/^\d{4}-\d{2}-\d{2}$/',$date)||!in_array($status,$allowed,true)){header('Location:index.php?msg='.urlencode('Invalid attendance selection.'));exit();}
$check=$conn->prepare('SELECT id FROM employees WHERE id=? AND status<>\'Inactive\'');$check->bind_param('i',$employee_id);$check->execute();if(!$check->get_result()->num_rows){header('Location:index.php?msg='.urlencode('Employee not found.'));exit();}
$existing=$conn->prepare('SELECT id FROM attendance WHERE employee_id=? AND attendance_date=? LIMIT 1');$existing->bind_param('is',$employee_id,$date);$existing->execute();$found=$existing->get_result()->fetch_assoc();
if($found){$stmt=$conn->prepare('UPDATE attendance SET status=?, check_in=CASE WHEN ? IN (\'Present\',\'Late\') AND check_in IS NULL THEN CURTIME() ELSE check_in END WHERE id=?');$stmt->bind_param('ssi',$status,$status,$found['id']);$stmt->execute();$message='Attendance updated successfully.';}else{$stmt=$conn->prepare('INSERT INTO attendance(employee_id,attendance_date,status,check_in) VALUES(?,?,?,CASE WHEN ? IN (\'Present\',\'Late\') THEN CURTIME() ELSE NULL END)');$stmt->bind_param('isss',$employee_id,$date,$status,$status);$stmt->execute();$message='Attendance marked successfully.';}
logActivity($conn,$_SESSION['user_id'],$_SESSION['fullname'],$message.' Employee ID: '.$employee_id);
header('Location:index.php?date='.urlencode($date).'&msg='.urlencode($message));exit();
