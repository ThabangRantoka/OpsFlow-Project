<?php

require_once("../config/auth.php");

require_once("../config/DataBase.php");

require_once("../config/permissions.php");


requireRole(["Admin", "Manager"]);


/*
|--------------------------------------------------------------------------
| Only POST requests
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {


    header("Location: add.php");

    exit();


}


/*
|--------------------------------------------------------------------------
| Get submitted values
|--------------------------------------------------------------------------
*/

$employee_number = trim($_POST["employee_number"] ?? "");

$fullname        = trim($_POST["fullname"] ?? "");

$gender          = trim($_POST["gender"] ?? "");

$email           = trim($_POST["email"] ?? "");

$phone           = trim($_POST["phone"] ?? "");

$department      = trim($_POST["department"] ?? "");

$position        = trim($_POST["position"] ?? "");

$salary          = trim($_POST["salary"] ?? "");

$hire_date       = trim($_POST["hire_date"] ?? "");

$status          = trim($_POST["status"] ?? "Active");


/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/

if (
    $employee_number === "" ||
    $fullname === "" ||
    $gender === "" ||
    $email === "" ||
    $department === ""
) {


    header(
        "Location: add.php?error=" .
        urlencode("Please complete all required employee fields.")
    );


    exit();


}


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {


    header(
        "Location: add.php?error=" .
        urlencode("Please enter a valid email address.")
    );


    exit();


}


if (!in_array($gender, ["Male", "Female"], true)) {


    header(
        "Location: add.php?error=" .
        urlencode("Invalid gender selected.")
    );


    exit();


}


if (!in_array($status, ["Active", "Inactive", "Leave"], true)) {


    header(
        "Location: add.php?error=" .
        urlencode("Invalid employee status.")
    );


    exit();


}


/*
|--------------------------------------------------------------------------
| Salary
|--------------------------------------------------------------------------
*/

if ($salary === "") {

    $salary = 0;

}


if (!is_numeric($salary)) {


    header(
        "Location: add.php?error=" .
        urlencode("Salary must be a valid number.")
    );


    exit();


}


$salary = (float)$salary;


/*
|--------------------------------------------------------------------------
| Hire date
|--------------------------------------------------------------------------
*/

if ($hire_date !== "") {


    $dateObject = DateTime::createFromFormat(
        "Y-m-d",
        $hire_date
    );


    if (
        !$dateObject ||
        $dateObject->format("Y-m-d") !== $hire_date
    ) {


        header(
            "Location: add.php?error=" .
            urlencode("Invalid hire date.")
        );


        exit();


    }


}


/*
|--------------------------------------------------------------------------
| Check employee number
|--------------------------------------------------------------------------
*/

$checkNumber = $conn->prepare("
    SELECT id
    FROM employees
    WHERE employee_number = ?
    LIMIT 1
");


if (!$checkNumber) {


    die(
        "Database error: " .
        htmlspecialchars($conn->error)
    );


}


$checkNumber->bind_param(
    "s",
    $employee_number
);


$checkNumber->execute();


$checkNumber->store_result();


if ($checkNumber->num_rows > 0) {


    $checkNumber->close();


    header(
        "Location: add.php?error=" .
        urlencode("Employee number already exists.")
    );


    exit();


}


$checkNumber->close();


/*
|--------------------------------------------------------------------------
| Check email
|--------------------------------------------------------------------------
*/

$checkEmail = $conn->prepare("
    SELECT id
    FROM employees
    WHERE email = ?
    LIMIT 1
");


if (!$checkEmail) {


    die(
        "Database error: " .
        htmlspecialchars($conn->error)
    );


}


$checkEmail->bind_param(
    "s",
    $email
);


$checkEmail->execute();


$checkEmail->store_result();


if ($checkEmail->num_rows > 0) {


    $checkEmail->close();


    header(
        "Location: add.php?error=" .
        urlencode("Email address already exists.")
    );


    exit();


}


$checkEmail->close();


/*
|--------------------------------------------------------------------------
| Insert employee
|--------------------------------------------------------------------------
*/

$sql = "
    INSERT INTO employees
    (
        employee_number,
        fullname,
        gender,
        email,
        phone,
        department,
        position,
        salary,
        hire_date,
        status
    )
    VALUES
    (
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        NULLIF(?, ''),
        ?
    )
";


$stmt = $conn->prepare($sql);


if (!$stmt) {


    die(
        "Could not prepare employee insert: " .
        htmlspecialchars($conn->error)
    );


}


$stmt->bind_param(
    "sssssssdss",
    $employee_number,
    $fullname,
    $gender,
    $email,
    $phone,
    $department,
    $position,
    $salary,
    $hire_date,
    $status
);


/*
|--------------------------------------------------------------------------
| Execute insert
|--------------------------------------------------------------------------
*/

if (!$stmt->execute()) {


    $error = $stmt->error;


    $stmt->close();


    header(
        "Location: add.php?error=" .
        urlencode(
            "Employee could not be saved: " . $error
        )
    );


    exit();


}


$newEmployeeId = $stmt->insert_id;


$stmt->close();


/*
|--------------------------------------------------------------------------
| Redirect back to employee list
|--------------------------------------------------------------------------
*/

header(
    "Location: index.php?success=" .
    urlencode(
        $fullname . " was added successfully."
    )
);


exit();


?>