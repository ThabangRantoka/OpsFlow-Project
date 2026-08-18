<?php
/*
======================================
OpsFlow - Update Attendance Record
======================================
*/

require_once("../config/auth.php");

require_once("../config/DataBase.php");

require_once("../config/permissions.php");

require_once("../config/functions.php");


requireRole(["Admin", "Manager"]);


if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location:index.php");

    exit();

}


$id          = isset($_POST["id"]) ? (int)$_POST["id"] : 0;

$employee_id = isset($_POST["employee_id"]) ? (int)$_POST["employee_id"] : 0;

$date        = isset($_POST["attendance_date"]) ? trim($_POST["attendance_date"]) : "";

$status      = isset($_POST["status"]) ? trim($_POST["status"]) : "Present";

$check_in    = isset($_POST["check_in"]) && $_POST["check_in"] !== "" ? $_POST["check_in"] : null;

$check_out   = isset($_POST["check_out"]) && $_POST["check_out"] !== "" ? $_POST["check_out"] : null;

$remarks     = isset($_POST["remarks"]) ? trim($_POST["remarks"]) : "";


$allowedStatus = ["Present", "Absent", "Late", "Leave"];


if ($id <= 0 || $employee_id <= 0 || $date === "" || !in_array($status, $allowedStatus, true)) {

    header("Location:index.php?msg=" . urlencode("Invalid attendance details."));

    exit();

}


/* Employee must exist */
$check = $conn->prepare("SELECT id FROM employees WHERE id=?");

$check->bind_param("i", $employee_id);

$check->execute();


if ($check->get_result()->num_rows === 0) {

    header("Location:index.php?msg=" . urlencode("Selected employee no longer exists."));

    exit();

}


/* No duplicate record for the same employee on the same day */
$dup = $conn->prepare("SELECT id FROM attendance WHERE employee_id=? AND attendance_date=? AND id<>?");

$dup->bind_param("isi", $employee_id, $date, $id);

$dup->execute();


if ($dup->get_result()->num_rows > 0) {

    header("Location:index.php?msg=" . urlencode("Another record already exists for this employee on this date."));

    exit();

}


$stmt = $conn->prepare("
UPDATE attendance
SET employee_id=?,
    attendance_date=?,
    status=?,
    check_in=?,
    check_out=?,
    remarks=?
WHERE id=?
");


$stmt->bind_param(
    "isssssi",
    $employee_id,
    $date,
    $status,
    $check_in,
    $check_out,
    $remarks,
    $id
);


if ($stmt->execute()) {


    logActivity(
        $conn,
        $_SESSION["user_id"],
        $_SESSION["fullname"],
        "Updated attendance record #" . $id
    );


    header("Location:index.php?msg=" . urlencode("Attendance updated successfully."));

    exit();


}
 else {


    header("Location:index.php?msg=" . urlencode("Could not update the attendance record."));

    exit();

}

