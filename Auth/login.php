<?php
session_start();

if (isset($_SESSION["user_id"])) {

    header("Location: ../Dashboard/index.php");

    exit();

}

$message = isset($_GET["msg"]) ? $_GET["msg"] : "";

$oldEmail = isset($_GET["email"]) ? $_GET["email"] : "";

$oldRole = isset($_GET["role"]) ? $_GET["role"] : "";

?>
<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>OpsFlow | Sign in</title>

<link rel="stylesheet" href="../Assets/CSS/style.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body class="auth-page">

<div class="auth-shell">

    <section class="auth-hero">

        <div class="auth-brand">
<span>O</span> OpsFlow</div>

        <div class="auth-hero-copy">

            <h1>One workspace for people,<br>projects and daily operations.</h1>

            <p>Secure, role-based and always up to date. The first account created becomes the system administrator.</p>

        </div>

        <div class="auth-footer">Business Operations Management System</div>

    </section>


    <section class="auth-form-side">

        <div class="auth-card">

            <h2>Welcome to OpsFlow</h2>

            <p class="auth-subtitle">Sign in to your operations workspace.</p>


            <div class="auth-tabs">

                <span class="active">Sign in</span>

                <a href="register.php">Create account</a>

            </div>


            
<?php if ($message !== "") {
 
?>
                <div class="auth-message">
<?php echo htmlspecialchars($message);
 
?></div>

            
<?php }
 
?>

            <form action="login_process.php" method="POST">

                <label>Email</label>

                <input type="email" name="email" value="
<?php echo htmlspecialchars($oldEmail);
 
?>" required>


                <label>Password</label>

                <input type="password" name="password" required>


                <label>Role</label>

                <select name="role" required>

                    <option value="">Select role</option>

                    <option value="Admin">Admin</option>

                    <option value="Manager">Manager</option>

                    <option value="Employee">Employee</option>

                </select>


                <button class="auth-button" type="submit">Sign in</button>

            </form>

        </div>

    </section>

</div>

</body>

</html>
