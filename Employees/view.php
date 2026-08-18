<?php
/*
==========================================
OpsFlow Enterprise Management System
Employee Profile
Developer: Moyahabo Thabang
Version : 4.0
==========================================
*/

require_once("../config/auth.php");

require_once("../config/permissions.php");

require_once("../config/DataBase.php");


// Allow only Admin, Manager and Employee
requireRole(["Admin","Manager","Employee"]);


if(!isset($_GET["id"]) || !is_numeric($_GET["id"])){

    header("Location:index.php");

    exit();

}


$id = (int)$_GET["id"];


$stmt = $conn->prepare("
SELECT *
FROM employees
WHERE id = ?
LIMIT 1
");


$stmt->bind_param("i", $id);

$stmt->execute();


$result = $stmt->get_result();


if($result->num_rows == 0){

    die("Employee not found.");

}


$employee = $result->fetch_assoc();

?>

<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Employee Profile</title>


<link rel="stylesheet" href="../Assets/CSS/style.css">

<link rel="stylesheet" href="../Assets/CSS/dashboard.css">


</head>


<body>


<div class="dashboard">


    
<?php include("../Includes/sidebar.php");
 
?>

    <div class="main-content">


        
<?php include("../Includes/header.php");
 
?>

        <div class="recent">


            <h2>👤 Employee Profile</h2>


            <br>


            <table>


                <tr>

                    <th width="220">Employee Number</th>

                    <td>
<?php echo htmlspecialchars($employee["employee_number"]);
 
?></td>

                </tr>


                <tr>

                    <th>Full Name</th>

                    <td>
<?php echo htmlspecialchars($employee["fullname"]);
 
?></td>

                </tr>


                <tr>

                    <th>Gender</th>

                    <td>
<?php echo htmlspecialchars($employee["gender"]);
 
?></td>

                </tr>


                <tr>

                    <th>Email</th>

                    <td>
<?php echo htmlspecialchars($employee["email"]);
 
?></td>

                </tr>


                <tr>

                    <th>Phone</th>

                    <td>
<?php echo htmlspecialchars($employee["phone"]);
 
?></td>

                </tr>


                <tr>

                    <th>Department</th>

                    <td>
<?php echo htmlspecialchars($employee["department"]);
 
?></td>

                </tr>


                <tr>

                    <th>Position</th>

                    <td>
<?php echo htmlspecialchars($employee["position"]);
 
?></td>

                </tr>


                <tr>

                    <th>Salary</th>

                    <td>M 
<?php echo number_format($employee["salary"], 2);
 
?></td>

                </tr>


                <tr>

                    <th>Hire Date</th>

                    <td>
<?php echo htmlspecialchars($employee["hire_date"]);
 
?></td>

                </tr>


                <tr>

                    <th>Status</th>

                    <td>


                    
<?php

                    if($employee["status"] == "Active"){


                        echo "<span class='badge active'>Active</span>";


                    }
elseif($employee["status"] == "Inactive"){


                        echo "<span class='badge inactive'>Inactive</span>";


                    }
else{


                        echo "<span class='badge leave'>Leave</span>";


                    }


                    
?>

                    </td>


                </tr>


            </table>


            <br>
<br>


            
<?php if($_SESSION["role"] == "Admin" || $_SESSION["role"] == "Manager"){
 
?>

                <a
                href="edit.php?id=
<?php echo $employee["id"];
 
?>"
                class="btn btn-primary">

                ✏ Edit Employee

                </a>


            
<?php }
 
?>

            <a
            href="index.php"
            class="btn btn-primary">

            ⬅ Back

            </a>


        </div>


    </div>


</div>


</body>


</html>