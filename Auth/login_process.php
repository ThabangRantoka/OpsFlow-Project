<?php
session_start();

require_once("../config/DataBase.php");
require_once("../config/functions.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit();
}

$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";

function loginFail($msg, $email = "")
{
    $url = "login.php?msg=" . urlencode($msg);
    if ($email !== "") {
        $url .= "&email=" . urlencode($email);
    }

    header("Location: " . $url);
    exit();
}

if ($email === "" || $password === "") {
    loginFail("Please enter your email and password.", $email);
}

$stmt = $conn->prepare("SELECT * FROM users WHERE email=? LIMIT 1");

if (!$stmt) {
    loginFail("Database error. Please try again.", $email);
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $stmt->close();
    loginFail("Email not found.", $email);
}

$user = $result->fetch_assoc();
$stmt->close();

// The role comes only from the authenticated database record.
// The user no longer needs to choose a role during sign in.
if (($user["status"] ?? "Active") !== "Active") {
    loginFail("Your account is deactivated. Contact the administrator.", $email);
}

if (!password_verify($password, $user["password"])) {
    loginFail("Invalid email or password.", $email);
}

if (($user["role"] ?? "") === "Employee") {
    $employeeId = ensureEmployeeRecord(
        $conn,
        (int)$user["id"],
        $user["fullname"],
        $user["email"],
        $user["phone"] ?? ""
    );

    if ($employeeId === false) {
        loginFail("Your employee profile could not be created. Please contact the administrator.", $email);
    }

    $user["employee_id"] = $employeeId;
}

session_regenerate_id(true);

$_SESSION["user_id"] = (int)$user["id"];
$_SESSION["fullname"] = $user["fullname"];
$_SESSION["email"] = $user["email"];
$_SESSION["role"] = $user["role"];
$_SESSION["employee_id"] = $user["employee_id"] ?? null;

logActivity(
    $conn,
    $_SESSION["user_id"],
    $_SESSION["fullname"],
    "Logged into the system as " . $user["role"]
);

header("Location: ../Dashboard/index.php");
exit();
