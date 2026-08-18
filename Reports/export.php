<?php

require_once("../config/auth.php");

require_once("../config/permissions.php");

require_once("../config/DataBase.php");


requireRole(["Admin","Manager"]);


header("Content-Type: text/csv");

header("Content-Disposition: attachment; filename=Attendance_Report.csv");


$output = fopen("php://output","w");


fputcsv($output,[
"Employee No",
"Employee Name",
"Date",
"Status",
"Check In",
"Check Out"
]);


$sql = "
SELECT
employees.employee_number,
employees.fullname,
attendance.attendance_date,
attendance.status,
attendance.check_in,
attendance.check_out
FROM attendance
INNER JOIN employees
ON attendance.employee_id = employees.id
ORDER BY attendance.attendance_date DESC
";


$result = $conn->query($sql);


while($row = $result->fetch_assoc()){


fputcsv($output,[

$row["employee_number"],
$row["fullname"],
$row["attendance_date"],
$row["status"],
$row["check_in"],
$row["check_out"]

]);


}


fclose($output);

exit();


?>