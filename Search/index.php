<?php
/*
==========================================
OpsFlow Enterprise Management System
Global Search
Developer: Moyahabo Thabang
Version: 1.0
==========================================
*/

require_once("../config/auth.php");

require_once("../config/DataBase.php");


$q = "";


if(isset($_GET["q"])){

    $q = trim($_GET["q"]);

}


$search = "%" . $q . "%";

?>

<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Global Search</title>


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


<h2>Global Search</h2>


<p>

Showing results for:

<strong>


<?php echo htmlspecialchars($q);
 
?>

</strong>


</p>


</div>



<div class="recent">


<h3>👥 Employees</h3>


<table>


<tr>


<th>Employee No</th>


<th>Full Name</th>


<th>Department</th>


<th>Position</th>


</tr>


<?php

$stmt = $conn->prepare("

SELECT *

FROM employees

WHERE

employee_number LIKE ?

OR fullname LIKE ?

OR email LIKE ?

");


$stmt->bind_param(

"sss",

$search,
$search,
$search

);


$stmt->execute();


$result = $stmt->get_result();


if($result->num_rows > 0){


while($row = $result->fetch_assoc()){


?>

<tr>


<td>
<?= htmlspecialchars($row["employee_number"]);
 
?></td>


<td>
<?= htmlspecialchars($row["fullname"]);
 
?></td>


<td>
<?= htmlspecialchars($row["department"]);
 
?></td>


<td>
<?= htmlspecialchars($row["position"]);
 
?></td>


</tr>


<?php

}


}
else{


echo "<tr><td colspan='4'>No employee found.</td></tr>";


}


?>

</table>


</div>


<div class="recent">


<h3>🏢 Departments</h3>


<table>


<tr>


<th>Name</th>


<th>Code</th>


</tr>


<?php

$stmt = $conn->prepare("

SELECT *

FROM departments

WHERE

department_name LIKE ?

OR department_code LIKE ?

");


$stmt->bind_param(

"ss",

$search,
$search

);


$stmt->execute();


$result = $stmt->get_result();


if($result->num_rows > 0){


while($row = $result->fetch_assoc()){


?>

<tr>


<td>
<?= htmlspecialchars($row["department_name"]);
 
?></td>


<td>
<?= htmlspecialchars($row["department_code"]);
 
?></td>


</tr>


<?php

}


}
else{


echo "<tr><td colspan='2'>No department found.</td></tr>";


}


?>

</table>


</div>


<div class="recent">


<h3>📁 Projects</h3>


<table>


<tr>


<th>Project</th>


<th>Status</th>


</tr>


<?php

$stmt = $conn->prepare("

SELECT *

FROM projects

WHERE

project_name LIKE ?

");


$stmt->bind_param(

"s",

$search

);


$stmt->execute();


$result = $stmt->get_result();


if($result->num_rows > 0){


while($row = $result->fetch_assoc()){


?>

<tr>


<td>
<?= htmlspecialchars($row["project_name"]);
 
?></td>


<td>
<?= htmlspecialchars($row["status"]);
 
?></td>


</tr>


<?php

}


}
else{


echo "<tr><td colspan='2'>No project found.</td></tr>";


}


?>

</table>


</div>


<div class="recent">


<h3>👤 Users</h3>


<table>


<tr>


<th>Full Name</th>


<th>Email</th>


<th>Role</th>


</tr>


<?php

$stmt = $conn->prepare("

SELECT *

FROM users

WHERE

fullname LIKE ?

OR email LIKE ?

");


$stmt->bind_param(

"ss",

$search,
$search

);


$stmt->execute();


$result = $stmt->get_result();


if($result->num_rows > 0){


while($row = $result->fetch_assoc()){


?>

<tr>


<td>
<?= htmlspecialchars($row["fullname"]);
 
?></td>


<td>
<?= htmlspecialchars($row["email"]);
 
?></td>


<td>
<?= htmlspecialchars($row["role"]);
 
?></td>


</tr>


<?php

}


}
else{


echo "<tr><td colspan='3'>No user found.</td></tr>";


}


?>

</table>


</div>


</div>


</div>


</body>


</html>