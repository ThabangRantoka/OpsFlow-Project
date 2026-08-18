<?php

/**
 * ==========================================================
 * OpsFlow Enterprise Management System
 * File: Projects/save.php
 * Description: Save New Project
 * ==========================================================
 */

require_once("../config/auth.php");

require_once("../config/DataBase.php");

require_once("../config/functions.php");

require_once("../config/permissions.php");


requireRole(["Admin", "Manager"]);



/*
|--------------------------------------------------------------------------
| Only POST requests are allowed
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {


    header("Location: index.php");

    exit();


}



/*
|--------------------------------------------------------------------------
| Read submitted values safely
|--------------------------------------------------------------------------
*/

$project_code = trim($_POST["project_code"] ?? '');


$project_name = trim($_POST["project_name"] ?? '');


$department = trim($_POST["department"] ?? '');


$project_manager = trim($_POST["project_manager"] ?? '');


$start_date = trim($_POST["start_date"] ?? '');


$end_date = trim($_POST["end_date"] ?? '');


$budget = $_POST["budget"] ?? '';


$progress = isset($_POST["progress"])
    ? (int)$_POST["progress"]
    : 0;


$priority = trim($_POST["priority"] ?? 'Medium');


$status = trim($_POST["status"] ?? 'Planning');



/*
|--------------------------------------------------------------------------
| Convert empty dates to NULL
|--------------------------------------------------------------------------
*/

if ($start_date === '') {

    $start_date = null;

}


if ($end_date === '') {

    $end_date = null;

}



/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/

$allowedPriorities = [
    'Low',
    'Medium',
    'High',
    'Critical'
];


$allowedStatuses = [
    'Planning',
    'In Progress',
    'Completed',
    'On Hold'
];



if ($project_code === '') {


    redirectWithError(
        "Project code is required.",
        $_POST
    );


}



if ($project_name === '') {


    redirectWithError(
        "Project name is required.",
        $_POST
    );


}



if ($department === '') {


    redirectWithError(
        "Please select a department.",
        $_POST
    );


}



if ($budget === '' || !is_numeric($budget)) {


    redirectWithError(
        "Please enter a valid project budget.",
        $_POST
    );


}



$budget = (float)$budget;



if ($budget < 0) {


    redirectWithError(
        "Project budget cannot be negative.",
        $_POST
    );


}



if ($progress < 0 || $progress > 100) {


    redirectWithError(
        "Progress must be between 0 and 100.",
        $_POST
    );


}



if (!in_array($priority, $allowedPriorities, true)) {


    redirectWithError(
        "Invalid project priority.",
        $_POST
    );


}



if (!in_array($status, $allowedStatuses, true)) {


    redirectWithError(
        "Invalid project status.",
        $_POST
    );


}



/*
|--------------------------------------------------------------------------
| Validate dates
|--------------------------------------------------------------------------
*/

if ($start_date !== null) {


    $startTimestamp = strtotime($start_date);


    if ($startTimestamp === false) {


        redirectWithError(
            "Invalid start date.",
            $_POST
        );


    }


}



if ($end_date !== null) {


    $endTimestamp = strtotime($end_date);


    if ($endTimestamp === false) {


        redirectWithError(
            "Invalid end date.",
            $_POST
        );


    }


}



if ($start_date !== null && $end_date !== null) {


    if ($end_date < $start_date) {


        redirectWithError(
            "End date cannot be before the start date.",
            $_POST
        );


    }


}



/*
|--------------------------------------------------------------------------
| Check duplicate project code
|--------------------------------------------------------------------------
*/

$check = $conn->prepare(
    "SELECT id FROM projects WHERE project_code = ? LIMIT 1"
);



if (!$check) {


    die(
        "Could not prepare duplicate check: " .
        htmlspecialchars($conn->error)
    );


}



$check->bind_param(
    "s",
    $project_code
);



if (!$check->execute()) {


    die(
        "Could not check project code: " .
        htmlspecialchars($check->error)
    );


}



$checkResult = $check->get_result();



if ($checkResult && $checkResult->num_rows > 0) {


    redirectWithError(
        "Project code already exists.",
        $_POST
    );


}



$check->close();



/*
|--------------------------------------------------------------------------
| Insert project
|--------------------------------------------------------------------------
*/

$sql = "
    INSERT INTO projects
    (
        project_code,
        project_name,
        department,
        project_manager,
        start_date,
        end_date,
        budget,
        progress,
        priority,
        status
    )
    VALUES
    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
";



$stmt = $conn->prepare($sql);



if (!$stmt) {


    die(
        "Could not prepare project insert: " .
        htmlspecialchars($conn->error)
    );


}



/*
|--------------------------------------------------------------------------
| Bind parameters
|--------------------------------------------------------------------------
|
| s = string
| d = decimal/double
| i = integer
|
| 6 strings + double + integer + 2 strings
|
*/

$stmt->bind_param(
    "ssssssdiss",
    $project_code,
    $project_name,
    $department,
    $project_manager,
    $start_date,
    $end_date,
    $budget,
    $progress,
    $priority,
    $status
);



/*
|--------------------------------------------------------------------------
| Execute
|--------------------------------------------------------------------------
*/

if (!$stmt->execute()) {


    $error = $stmt->error;


    $stmt->close();


    die(
        "Project could not be saved.<br><br>" .
        "Database error: " .
        htmlspecialchars($error)
    );


}



/*
|--------------------------------------------------------------------------
| Get newly-created project ID
|--------------------------------------------------------------------------
*/

$newProjectId = $stmt->insert_id;


$stmt->close();



/*
|--------------------------------------------------------------------------
| Activity log
|--------------------------------------------------------------------------
*/

if (function_exists('logActivity')) {


    $userId = isset($_SESSION["user_id"])
        ? (int)$_SESSION["user_id"]
        : 0;


    $fullname = $_SESSION["fullname"] ?? 'System';


    logActivity(
        $conn,
        $userId,
        $fullname,
        "Created project: " . $project_name
    );


}



/*
|--------------------------------------------------------------------------
| Success
|--------------------------------------------------------------------------
*/

header(
    "Location: index.php?msg=" .
    urlencode("Project created successfully.")
);


exit();



/*
|--------------------------------------------------------------------------
| Error redirect helper
|--------------------------------------------------------------------------
*/

function redirectWithError($message, $data = [])
{

    $query = [
        'msg' => $message,
        'type' => 'error'
    ];



    /*
    | Preserve useful form values
    */

    $fields = [
        'project_code',
        'project_name',
        'department',
        'project_manager',
        'start_date',
        'end_date',
        'budget',
        'progress',
        'priority',
        'status'
    ];



    foreach ($fields as $field) {


        if (isset($data[$field])) {


            $query[$field] = $data[$field];


        }


    }



    header(
        "Location: add.php?" .
        http_build_query($query)
    );


    exit();

}
