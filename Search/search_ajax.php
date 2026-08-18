<?php

require_once("../config/auth.php");

require_once("../config/DataBase.php");


$q = "";


if(isset($_GET["q"])){

    $q = trim($_GET["q"]);

}


if($q==""){

    exit();

}


$search="%".$q."%";


/*
=====================================
Employees
=====================================
*/

$stmt=$conn->prepare("
SELECT
id,
fullname,
employee_number
FROM employees
WHERE
fullname LIKE ?
OR employee_number LIKE ?
LIMIT 5
");


$stmt->bind_param("ss",$search,$search);


$stmt->execute();


$result=$stmt->get_result();


if($result->num_rows>0){


echo "<div class='search-title'>👥 Employees</div>";


while($row=$result->fetch_assoc()){


echo "

<a class='live-item'

href='../Employees/view.php?id=".$row["id"]."'>

<strong>".$row["fullname"]."</strong>

<br>

<small>".$row["employee_number"]."</small>

</a>

";


}


}


/*
=====================================
Departments
=====================================
*/

$stmt=$conn->prepare("
SELECT
id,
department_name
FROM departments
WHERE
department_name LIKE ?
LIMIT 5
");


$stmt->bind_param("s",$search);


$stmt->execute();


$result=$stmt->get_result();


if($result->num_rows>0){


echo "<div class='search-title'>🏢 Departments</div>";


while($row=$result->fetch_assoc()){


echo "

<a class='live-item'

href='../Departments/view.php?id=".$row["id"]."'>

".$row["department_name"]."

</a>

";


}


}


/*
=====================================
Projects
=====================================
*/

$stmt=$conn->prepare("
SELECT
id,
project_name
FROM projects
WHERE
project_name LIKE ?
LIMIT 5
");


$stmt->bind_param("s",$search);


$stmt->execute();


$result=$stmt->get_result();


if($result->num_rows>0){


echo "<div class='search-title'>📁 Projects</div>";


while($row=$result->fetch_assoc()){


echo "

<a class='live-item'

href='../Projects/view.php?id=".$row["id"]."'>

".$row["project_name"]."

</a>

";


}


}


/*
=====================================
Users
=====================================
*/

$stmt=$conn->prepare("
SELECT
id,
fullname,
role
FROM users
WHERE
fullname LIKE ?
LIMIT 5
");


$stmt->bind_param("s",$search);


$stmt->execute();


$result=$stmt->get_result();


if($result->num_rows>0){


echo "<div class='search-title'>👤 Users</div>";


while($row=$result->fetch_assoc()){


echo "

<a class='live-item'

href='../Users/view.php?id=".$row["id"]."'>

<strong>".$row["fullname"]."</strong>

<br>

<small>".$row["role"]."</small>

</a>

";


}


}


$conn->close();


?>