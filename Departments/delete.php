<?php

require_once("../config/auth.php");

require_once("../config/DataBase.php");


require_once("../config/permissions.php");


// Only Admin can delete
requireRole(["Admin"]);


$id = intval($_GET["id"]);


// Get department name
$stmt = $conn->prepare("
SELECT department_name
FROM departments
WHERE id=?
");


$stmt->bind_param("i",$id);


$stmt->execute();


$result = $stmt->get_result();


$department = $result->fetch_assoc();


$departmentName = $department["department_name"];


// Check employees
$check = $conn->prepare("
SELECT COUNT(*) AS total
FROM employees
WHERE department=?
");


$check->bind_param(
"s",
$departmentName
);


$check->execute();


$count = $check->get_result()->fetch_assoc();


if($count["total"]>0){


echo "<script>

alert('Cannot delete. Employees belong to this department.');

window.location='index.php';

</script>";


exit();


}


// Delete
$delete = $conn->prepare("
DELETE FROM departments
WHERE id=?
");


$delete->bind_param(
"i",
$id
);


$delete->execute();


header("Location:index.php");


exit();
