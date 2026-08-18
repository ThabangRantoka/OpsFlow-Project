<?php
/*
======================================
OpsFlow Enterprise Management System
Developer : Moyahabo Thabang
Version : 1.1
======================================
*/

session_start();

require_once("../config/DataBase.php");
require_once("../config/functions.php");

$message = "";

// Register User
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullname = trim($_POST["fullname"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirm_password"] ?? "";
    $role = trim($_POST["role"] ?? "");

    $allowedRoles = ["Admin", "Manager", "Employee"];

    // Validate required fields
    if ($fullname === "" || $email === "" || $password === "" || $confirmPassword === "" || $role === "") {
        $message = "Please complete all fields.";
    } elseif (!in_array($role, $allowedRoles, true)) {
        $message = "Please select a valid account role.";
    } elseif ($password !== $confirmPassword) {
        $message = "Passwords do not match.";
    } else {

        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");

        if (!$check) {
            $message = "Database error. Please try again.";
        } else {
            $check->bind_param("s", $email);
            $check->execute();
            $result = $check->get_result();

            if ($result->num_rows > 0) {
                $message = "Email already exists.";
            } else {

                // Encrypt Password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Username (kept unique-ish, derived from the email)
                $username = substr(
                    preg_replace("/[^a-zA-Z0-9._-]/", "", explode("@", $email)[0]),
                    0,
                    50
                );

                // Insert User with the role selected during registration
                $sql = "INSERT INTO users(fullname,username,email,password,role)
                        VALUES(?,?,?,?,?)";

                $stmt = $conn->prepare($sql);

                if (!$stmt) {
                    $message = "Database error. Please try again.";
                } else {
                    $stmt->bind_param(
                        "sssss",
                        $fullname,
                        $username,
                        $email,
                        $hashedPassword,
                        $role
                    );

                    if ($stmt->execute()) {
                        $newUserId = (int)$stmt->insert_id;

                        if ($role === "Employee") {
                            if (ensureEmployeeRecord($conn, $newUserId, $fullname, $email)) {
                                header("Location: login.php?msg=" . urlencode("Registration successful. Please sign in."));
                                exit();
                            }

                            $conn->query("DELETE FROM users WHERE id=" . $newUserId);
                            $message = "Registration failed. Employee profile could not be created.";
                        } else {
                            header("Location: login.php?msg=" . urlencode("Registration successful. Please sign in."));
                            exit();
                        }
                    }

                    $message = "Registration failed. Please try again.";
                    $stmt->close();
                }
            }

            $check->close();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>OpsFlow | Create account</title>
<link rel="stylesheet" href="../Assets/CSS/style.css">
</head>
<body class="auth-page">
<div class="auth-shell">
    <section class="auth-hero">
        <div class="auth-brand"><span>O</span> OpsFlow</div>
        <div class="auth-hero-copy">
            <h1>One workspace for people,<br>projects and daily operations.</h1>
            <p>Secure, role-based and always up to date. Create your workspace account and start managing operations.</p>
        </div>
        <div class="auth-footer">Business Operations Management System</div>
    </section>

    <section class="auth-form-side">
        <div class="auth-card">
            <h2>Create your OpsFlow account</h2>
            <p class="auth-subtitle">Set up access to your operations workspace.</p>

            <div class="auth-tabs">
                <a href="login.php">Sign in</a>
                <span class="active">Create account</span>
            </div>

            <?php if ($message !== "") { ?>
                <div class="auth-message"><?php echo htmlspecialchars($message, ENT_QUOTES, "UTF-8"); ?></div>
            <?php } ?>

            <form method="POST">
                <label>Full name</label>
                <input
                    type="text"
                    name="fullname"
                    value="<?php echo htmlspecialchars($_POST["fullname"] ?? "", ENT_QUOTES, "UTF-8"); ?>"
                    required
                >

                <label>Email</label>
                <input
                    type="email"
                    name="email"
                    value="<?php echo htmlspecialchars($_POST["email"] ?? "", ENT_QUOTES, "UTF-8"); ?>"
                    required
                >

                <label>Account role</label>
                <select name="role" required>
                    <option value="">Choose your role</option>
                    <option value="Admin" <?php echo (($_POST["role"] ?? "") === "Admin") ? "selected" : ""; ?>>Admin</option>
                    <option value="Manager" <?php echo (($_POST["role"] ?? "") === "Manager") ? "selected" : ""; ?>>Manager</option>
                    <option value="Employee" <?php echo (($_POST["role"] ?? "") === "Employee") ? "selected" : ""; ?>>Employee</option>
                </select>

                <label>Password</label>
                <input type="password" name="password" required>

                <label>Confirm password</label>
                <input type="password" name="confirm_password" required>

                <button class="auth-button" type="submit">Create account</button>
            </form>
        </div>
    </section>
</div>
</body>
</html>
