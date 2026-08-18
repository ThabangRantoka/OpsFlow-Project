<?php

require_once("../config/auth.php");

require_once("../config/DataBase.php");

require_once("../config/permissions.php");

requireRole("Admin");


if(!isset($_GET["id"])){


    header("Location:index.php");

    exit();


}


$id = intval($_GET["id"]);


// Default password
$newPassword = password_hash("123456", PASSWORD_DEFAULT);


$stmt = $conn->prepare("
UPDATE users
SET password=?
WHERE id=?
");


$stmt->bind_param("si",$newPassword,$id);


if($stmt->execute()){


    echo "<script>

    alert('Password reset successfully.\nDefault password is: 123456');

    window.location='index.php';

    </script>";


}
else{


    echo $conn->error;


}

?>