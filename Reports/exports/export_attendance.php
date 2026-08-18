<?php

require_once("../../config/auth.php");

require_once("../../config/permissions.php");

require_once("../../config/DataBase.php");


requireRole(["Admin","Manager"]);


header("Content-Type:text/csv");

header("Content-Disposition: attachment; filename=attendance_report.csv");


$output=fopen("php://output","w");


fputcsv($output,[
"Employee Number",
"Employee",
"Date",
"Status",
"Check In",
"Check Out"
]);


$result=$conn->query("
SELECT
employees.employee_number,
employees.fullname,
attendance.attendance_date,
attendance.status,
attendance.check_in,
attendance.check_out
FROM attendance
INNER JOIN employees
ON attendance.employee_id=employees.id
ORDER BY attendance.attendance_date DESC
");


while($row=$result->fetch_assoc()){

    fputcsv($output,$row);

}


fclose($output);

exit();
