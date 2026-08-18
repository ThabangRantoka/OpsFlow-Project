<?php

require_once("../../config/auth.php");
require_once("../../config/permissions.php");
require_once("../../config/DataBase.php");

requireRole(["Admin","Manager"]);

header("Content-Type:text/csv");
header("Content-Disposition: attachment; filename=projects_report.csv");

$output=fopen("php://output","w");

fputcsv($output,[
"Project Code",
"Project",
"Department",
"Manager",
"Progress",
"Priority",
"Status"
]);

$result=$conn->query("
SELECT project_code,
project_name,
department,
project_manager,
progress,
priority,
status
FROM projects
ORDER BY project_name
");

while($row=$result->fetch_assoc()){
    fputcsv($output,$row);
}

fclose($output);
exit();