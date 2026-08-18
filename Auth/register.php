<?php
/*
======================================
OpsFlow Enterprise Management System
Developer : Moyahabo Thabang
Version : 1.0
======================================
*/

session_start();


require_once("../config/DataBase.php");


$message = "";


// Register User
if($_SERVER["REQUEST_METHOD"] == "POST"){


    $fullname = trim($_POST["fullname"]);

    $email = trim($_POST["email"]);

    $password = $_POST["password"];

    $confirmPassword = $_POST["confirm_password"];

    $role = "Employee";

    $existingUsers = (int)$conn->query("SELECT COUNT(*) total FROM users")->fetch_assoc()["total"];

    if ($existingUsers === 0) {
 $role = "Admin";
 }


    // Check passwords match
    if($password != $confirmPassword){


        $message = "Passwords do not match.";


    }
else{


        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM users WHERE email=?");


        $check->bind_param("s",$email);


        $check->execute();


        $result = $check->get_result();


        if($result->num_rows > 0){


            $message = "Email already exists.";


        }
else{


            // Encrypt Password
            $hashedPassword = password_hash($password,PASSWORD_DEFAULT);


            // Username (kept unique-ish, derived from the email)
            $username = substr(preg_replace("/[^a-zA-Z0-9._-]/", "", explode("@", $email)[0]), 0, 50);


            // Insert User
            $sql = "INSERT INTO users(fullname,username,email,password,role)
                    VALUES(?,?,?,?,?)";


            $stmt = $conn->prepare($sql);


            $stmt->bind_param(
                "sssss",
                $fullname,
                $username,
                $email,
                $hashedPassword,
                $role
            );



            if($stmt->execute()){


                header("Location: login.php?msg=Registration successful. Please login.");

                exit();


            }
else{


                $message = "Registration failed.";


            }


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

        <div class="auth-brand">
<span>O</span> OpsFlow</div>

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

            
<?php if($message!=""){
 
?>
                <div class="auth-message">
<?php echo htmlspecialchars($message);
 
?></div>

            
<?php }
 
?>
            <form method="POST">

                <label>Full name</label>

                <input type="text" name="fullname" required>

                <label>Email</label>

                <input type="email" name="email" required>

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
