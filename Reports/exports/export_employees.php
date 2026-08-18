<?php

require_once("../../config/auth.php");

require_once("../../config/permissions.php");

require_once("../../config/DataBase.php");


requireRole(["Admin","Manager"]);


header("Content-Type: text/csv");

header("Content-Disposition: attachment; filename=employees_report.csv");


$output = fopen("php://output", "w");


fputcsv($output, [
    "Employee Number",
    "Full Name",
    "Department",
    "Position",
    "Email",
    "Status"
]);


$result = $conn->query("
SELECT employee_number, fullname, department, position, email, status
FROM employees
ORDER BY fullname ASC
");


while($row = $result->fetch_assoc()){

    fputcsv($output,$row);

}


fclose($output);

exit();
