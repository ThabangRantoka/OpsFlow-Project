<?php

require_once("../config/auth.php");

require_once("../config/DataBase.php");

require_once("../config/permissions.php");


requireRole(["Admin", "Manager"]);


/*
|--------------------------------------------------------------------------
| Load departments
|--------------------------------------------------------------------------
*/

$departments = [];


$result = $conn->query("
    SELECT
        department_name
    FROM departments
    ORDER BY department_name ASC
");


if ($result) {


    while ($row = $result->fetch_assoc()) {


        $departments[] = $row["department_name"];


    }


    $result->free();


}


$error = trim($_GET["error"] ?? "");


?>

<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>


<title>OpsFlow | Add Employee</title>


<link
    rel="stylesheet"
    href="../Assets/CSS/style.css"
>


<link
    rel="stylesheet"
    href="../Assets/CSS/dashboard.css"
>


<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
>


<style>

.employee-form-card {
    background: #fff;
    border: 1px solid #e6ebf1;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(21,42,64,.04);
    padding: 28px;
    max-width: 950px;
}

.employee-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
}

.employee-form-group {
    display: flex;
    flex-direction: column;
}

.employee-form-group.full {
    grid-column: 1 / -1;
}

.employee-form-group label {
    margin-bottom: 8px;
    color: #334155;
    font-size: 13px;
    font-weight: 600;
}

.employee-form-group input,
.employee-form-group select {
    width: 100%;
    height: 44px;
    padding: 0 13px;
    border: 1px solid #dce3ec;
    border-radius: 9px;
    background: #fff;
    color: #243348;
    font-size: 14px;
    outline: none;
}

.employee-form-group input:focus,
.employee-form-group select:focus {
    border-color: #4d9ab8;
    box-shadow: 0 0 0 3px rgba(22,125,176,.08);
}

.employee-form-actions {
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #edf0f4;
    display: flex;
    gap: 10px;
}

.employee-form-error {
    padding: 12px 14px;
    border-radius: 10px;
    background: #fff0f1;
    color: #c63d4c;
    border: 1px solid #f4c6cb;
    margin-bottom: 20px;
    font-size: 13px;
}

@media(max-width: 760px) {

    .employee-form-grid {
        grid-template-columns: 1fr;
    }

    .employee-form-group.full {
        grid-column: auto;
    }

}

</style>


</head>


<body>


<div class="dashboard">


<?php include("../Includes/sidebar.php");
 
?>

<main class="main-content">


<?php include("../Includes/header.php");
 
?>

<section class="page-heading">


    <div class="page-toolbar">


        <div>


            <h1>Add employee</h1>


            <p>
                Add a new employee to your OpsFlow workspace
            </p>


        </div>


    </div>


</section>


<?php if ($error !== "") {
 
?>

    <div class="employee-form-error">


        
<?php echo htmlspecialchars(
            $error,
            ENT_QUOTES,
            "UTF-8"
        );
 
?>

    </div>


<?php }
 
?>

<div class="employee-form-card">


<form
    method="POST"
    action="save.php"
>


<div class="employee-form-grid">


    <!-- Employee number -->


    <div class="employee-form-group">


        <label>
            Employee number
        </label>


        <input
            type="text"
            name="employee_number"
            placeholder="EMP008"
            required
        >


    </div>


    <!-- Full name -->


    <div class="employee-form-group">


        <label>
            Full name
        </label>


        <input
            type="text"
            name="fullname"
            placeholder="Full name"
            required
        >


    </div>


    <!-- Gender -->


    <div class="employee-form-group">


        <label>
            Gender
        </label>


        <select
            name="gender"
            required
        >


            <option value="">
                Select gender
            </option>


            <option value="Male">
                Male
            </option>


            <option value="Female">
                Female
            </option>


        </select>


    </div>


    <!-- Email -->


    <div class="employee-form-group">


        <label>
            Email address
        </label>


        <input
            type="email"
            name="email"
            placeholder="employee@example.com"
            required
        >


    </div>


    <!-- Phone -->


    <div class="employee-form-group">


        <label>
            Phone number
        </label>


        <input
            type="text"
            name="phone"
            placeholder="Phone number"
        >


    </div>


    <!-- Department -->


    <div class="employee-form-group">


        <label>
            Department
        </label>


        <select
            name="department"
            required
        >


            <option value="">
                Select department
            </option>


            
<?php foreach ($departments as $department) {
 
?>

                <option
                    value="
<?php echo htmlspecialchars(
                        $department,
                        ENT_QUOTES,
                        "UTF-8"
                    );
 
?>"
                >


                    
<?php echo htmlspecialchars(
                        $department,
                        ENT_QUOTES,
                        "UTF-8"
                    );
 
?>

                </option>


            
<?php }
 
?>

        </select>


    </div>


    <!-- Position -->


    <div class="employee-form-group">


        <label>
            Position
        </label>


        <input
            type="text"
            name="position"
            placeholder="Job position"
        >


    </div>


    <!-- Salary -->


    <div class="employee-form-group">


        <label>
            Salary
        </label>


        <input
            type="number"
            name="salary"
            step="0.01"
            min="0"
            placeholder="0.00"
        >


    </div>


    <!-- Hire date -->


    <div class="employee-form-group">


        <label>
            Hire date
        </label>


        <input
            type="date"
            name="hire_date"
        >


    </div>


    <!-- Status -->


    <div class="employee-form-group">


        <label>
            Status
        </label>


        <select
            name="status"
        >


            <option value="Active">
                Active
            </option>


            <option value="Inactive">
                Inactive
            </option>


            <option value="Leave">
                On Leave
            </option>


        </select>


    </div>


</div>


<div class="employee-form-actions">


    <button
        type="submit"
        class="btn btn-primary"
    >

        <i class="fa-solid fa-check">
</i>
        Save employee
    </button>


    <a
        href="index.php"
        class="btn btn-light"
    >
        Cancel
    </a>


</div>


</form>


</div>


</main>


</div>


</body>


</html>