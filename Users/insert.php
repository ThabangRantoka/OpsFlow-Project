<?php
require_once("../config/auth.php");
require_once("../config/permissions.php");
require_once("../config/DataBase.php");
require_once("../config/functions.php");
requireRole("Admin");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location:index.php"); exit();
}

$fullname = trim($_POST["fullname"] ?? "");
$username = trim($_POST["username"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$passwordPlain = $_POST["password"] ?? "";
$confirm = $_POST["confirm_password"] ?? "";
$role = $_POST["role"] ?? "Employee";
$status = $_POST["status"] ?? "Active";

if ($fullname === "" || $username === "" || !filter_var($email, FILTER_VALIDATE_EMAIL) || $passwordPlain === "" || $passwordPlain !== $confirm || !in_array($role,["Admin","Manager","Employee"],true) || !in_array($status,["Active","Inactive"],true)) {
    header("Location:add.php?msg=" . urlencode("Please complete the user form correctly.")); exit();
}

$check = $conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
$check->bind_param("s", $email); $check->execute();
if ($check->get_result()->num_rows) {
    header("Location:add.php?msg=" . urlencode("Email already exists.")); exit();
}

$photo = "";
if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));
    if (in_array($ext,["jpg","jpeg","png","webp","gif"],true)) {
        $dir = __DIR__ . "/photos";
        if (!is_dir($dir)) mkdir($dir,0755,true);
        $photo = time() . "_" . preg_replace('/[^a-zA-Z0-9._-]/','_',basename($_FILES["photo"]["name"]));
        move_uploaded_file($_FILES["photo"]["tmp_name"], $dir . "/" . $photo);
    }
}

$password = password_hash($passwordPlain, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users(fullname,username,email,phone,photo,password,role,status) VALUES(?,?,?,?,?,?,?,?)");
$stmt->bind_param("ssssssss",$fullname,$username,$email,$phone,$photo,$password,$role,$status);
if ($stmt->execute()) {
    $newUserId = (int)$stmt->insert_id;

    if ($role === "Employee") {
        if (!ensureEmployeeRecord($conn, $newUserId, $fullname, $email, $phone)) {
            $conn->query("DELETE FROM users WHERE id=" . $newUserId);
            header("Location:add.php?msg=" . urlencode("Unable to create the employee profile."));
            exit();
        }
    }

    logActivity($conn,$_SESSION["user_id"],$_SESSION["fullname"],"Created user: " . $fullname);
    header("Location:index.php?success=" . urlencode("User created successfully.")); exit();
}
header("Location:add.php?msg=" . urlencode("Unable to create user: " . $conn->error)); exit();
