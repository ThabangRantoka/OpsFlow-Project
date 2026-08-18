<?php
require_once("../config/auth.php");
require_once("../config/DataBase.php");
require_once("../config/permissions.php");

requireRole(["Admin", "Manager", "Employee"]);



/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function employeeInitials($name)
{
    $name = trim((string)$name);

    if ($name === '') {
        return "EM";
    }

    $parts = preg_split('/\s+/', $name);

    $initials = '';

    foreach (array_slice($parts, 0, 2) as $part) {
        if ($part !== '') {
            $initials .= strtoupper(substr($part, 0, 1));
        }
    }

    return $initials ?: "EM";
}

function employeeStatusClass($status)
{
    switch ($status) {
        case "Active":
            return "active";

        case "Leave":
            return "leave";

        case "Inactive":
        default:
            return "inactive";
    }
}

/*
|--------------------------------------------------------------------------
| Filters
|--------------------------------------------------------------------------
*/

$search = trim($_GET["search"] ?? "");
$department = trim($_GET["department"] ?? "");
$status = trim($_GET["status"] ?? "");

$where = [];
$params = [];
$types = "";

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

if ($search !== "") {

    $where[] = "
        (
            fullname LIKE ?
            OR employee_number LIKE ?
            OR email LIKE ?
            OR position LIKE ?
        )
    ";

    $like = "%" . $search . "%";

    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;

    $types .= "ssss";
}

/*
|--------------------------------------------------------------------------
| Department filter
|--------------------------------------------------------------------------
*/

if ($department !== "") {

    $where[] = "department = ?";

    $params[] = $department;

    $types .= "s";
}

/*
|--------------------------------------------------------------------------
| Status filter
|--------------------------------------------------------------------------
*/

if ($status !== "") {

    $where[] = "status = ?";

    $params[] = $status;

    $types .= "s";
}

/*
|--------------------------------------------------------------------------
| Employee query
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        id,
        employee_number,
        fullname,
        gender,
        email,
        phone,
        department,
        position,
        salary,
        hire_date,
        status,
        created_at
    FROM employees
";

if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY id DESC";

/*
|--------------------------------------------------------------------------
| Execute employee query
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die(
        "Employee query preparation failed: "
        . e($conn->error)
    );
}

if (!empty($params)) {

    $stmt->bind_param(
        $types,
        ...$params
    );
}

if (!$stmt->execute()) {

    die(
        "Employee query failed: "
        . e($stmt->error)
    );
}

/*
|--------------------------------------------------------------------------
| IMPORTANT
|--------------------------------------------------------------------------
| We use bind_result() instead of get_result().
| This makes the page work even when PHP/mysqlnd is configured differently.
|--------------------------------------------------------------------------
*/

$stmt->store_result();

$stmt->bind_result(
    $employeeId,
    $employeeNumber,
    $fullname,
    $gender,
    $email,
    $phone,
    $employeeDepartment,
    $position,
    $salary,
    $hireDate,
    $employeeStatus,
    $createdAt
);

$employees = [];

while ($stmt->fetch()) {

    $employees[] = [
        "id" => $employeeId,
        "employee_number" => $employeeNumber,
        "fullname" => $fullname,
        "gender" => $gender,
        "email" => $email,
        "phone" => $phone,
        "department" => $employeeDepartment,
        "position" => $position,
        "salary" => $salary,
        "hire_date" => $hireDate,
        "status" => $employeeStatus,
        "created_at" => $createdAt
    ];
}

$stmt->close();

/*
|--------------------------------------------------------------------------
| Department list
|--------------------------------------------------------------------------
*/

$departments = [];

$departmentQuery = $conn->query("
    SELECT department_name
    FROM departments
    ORDER BY department_name ASC
");

if ($departmentQuery) {

    while ($row = $departmentQuery->fetch_assoc()) {
        $departments[] = $row["department_name"];
    }

    $departmentQuery->free();
}

/*
|--------------------------------------------------------------------------
| Total employee count
|--------------------------------------------------------------------------
*/

$count = 0;

$countQuery = $conn->query("
    SELECT COUNT(*) AS total
    FROM employees
");

if ($countQuery) {

    $countRow = $countQuery->fetch_assoc();

    $count = (int)$countRow["total"];

    $countQuery->free();
}

/*
|--------------------------------------------------------------------------
| Success / error messages
|--------------------------------------------------------------------------
*/

$success = trim($_GET["success"] ?? "");
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

<title>OpsFlow | Employees</title>

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

/*
|--------------------------------------------------------------------------
| Employees page specific styling
|--------------------------------------------------------------------------
*/

.employees-page-card {
    background: #ffffff;
    border: 1px solid #e6ebf1;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(21,42,64,.04);
    overflow: hidden;
}

.employees-filter {
    padding: 18px;
    border-bottom: 1px solid #edf0f4;
}

.employees-filter-form {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.employees-filter-form input,
.employees-filter-form select {
    height: 42px;
    padding: 0 13px;
    border: 1px solid #dce3ec;
    border-radius: 9px;
    background: #ffffff;
    color: #334155;
    font-size: 14px;
    outline: none;
}

.employees-filter-form input {
    flex: 1;
    min-width: 280px;
}

.employees-filter-form input:focus,
.employees-filter-form select:focus {
    border-color: #4d9ab8;
    box-shadow: 0 0 0 3px rgba(22,125,176,.08);
}

.employees-table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.ops-employees-table {
    width: 100%;
    min-width: 950px;
    border-collapse: collapse;
    background: #ffffff;
}

.ops-employees-table thead {
    background: #ffffff;
}

.ops-employees-table th {
    padding: 16px 18px;
    text-align: left;
    color: #687a8f;
    font-size: 13px;
    font-weight: 600;
    border-bottom: 1px solid #e5eaf0;
    white-space: nowrap;
}

.ops-employees-table td {
    padding: 13px 18px;
    border-bottom: 1px solid #edf0f4;
    color: #243348;
    font-size: 14px;
    vertical-align: middle;
}

.ops-employees-table tbody tr:hover {
    background: #fafbfd;
}

.employee-cell-new {
    display: flex;
    align-items: center;
    gap: 12px;
}

.employee-avatar-new {
    width: 40px;
    height: 40px;
    min-width: 40px;
    border-radius: 50%;
    background: #e8f3f7;
    color: #187a9e;
    display: grid;
    place-items: center;
    font-size: 12px;
    font-weight: 700;
}

.employee-name-new {
    display: block;
    color: #17263a;
    font-size: 14px;
    font-weight: 600;
}

.employee-meta-new {
    display: block;
    margin-top: 3px;
    color: #8190a2;
    font-size: 12px;
}

.employee-money {
    white-space: nowrap;
    color: #17263a !important;
    font-variant-numeric: tabular-nums;
}

.employee-actions-new {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 14px;
    white-space: nowrap;
}

.employee-actions-new a {
    text-decoration: none;
    font-size: 15px;
}

.employee-view {
    color: #167db0;
}

.employee-edit {
    color: #26384c;
}

.employee-delete {
    color: #e24755;
}

.employee-empty {
    padding: 65px 25px !important;
    text-align: center;
    color: #708095 !important;
}

.employee-empty strong {
    display: block;
    color: #26384c;
    font-size: 16px;
    margin-bottom: 7px;
}

.employee-alert {
    padding: 12px 14px;
    border-radius: 10px;
    margin-bottom: 18px;
    font-size: 13px;
}

.employee-alert-success {
    background: #e9f7ef;
    color: #18784f;
    border: 1px solid #c2e5d1;
}

.employee-alert-error {
    background: #fff0f1;
    color: #c63d4c;
    border: 1px solid #f4c6cb;
}

@media(max-width: 760px) {

    .employees-filter-form {
        align-items: stretch;
        flex-direction: column;
    }

    .employees-filter-form input {
        min-width: 100%;
        width: 100%;
    }

    .employees-filter-form select {
        width: 100%;
    }

}

</style>

</head>

<body>

<div class="dashboard">

<?php include("../Includes/sidebar.php"); ?>

<main class="main-content">

<?php include("../Includes/header.php"); ?>

<section class="page-heading">

    <div class="page-toolbar">

        <div>

            <h1>Employees</h1>

            <p>
                <?php echo $count; ?>
                people on record
            </p>

        </div>

        <div class="actions">

            <a
                href="../Reports/exports/export_employees.php"
                class="btn btn-light"
            >
                <i class="fa-solid fa-download"></i>
                Export
            </a>

            <?php
            if (
                isset($_SESSION["role"]) &&
                in_array(
                    $_SESSION["role"],
                    ["Admin", "Manager"],
                    true
                )
            ) {
            ?>

                <a
                    href="add.php"
                    class="btn btn-primary"
                >
                    <i class="fa-solid fa-plus"></i>
                    Add employee
                </a>

            <?php } ?>

        </div>

    </div>

</section>

<?php if ($success !== "") { ?>

    <div class="employee-alert employee-alert-success">
        <?php echo e($success); ?>
    </div>

<?php } ?>

<?php if ($error !== "") { ?>

    <div class="employee-alert employee-alert-error">
        <?php echo e($error); ?>
    </div>

<?php } ?>

<div class="employees-page-card">

    <!-- FILTERS -->

    <div class="employees-filter">

        <form
            class="employees-filter-form"
            method="GET"
            action="index.php"
        >

            <input
                type="text"
                name="search"
                value="<?php echo e($search); ?>"
                placeholder="Search name, number, email or position"
            >

            <select name="department">

                <option value="">
                    All departments
                </option>

                <?php foreach ($departments as $dept) { ?>

                    <option
                        value="<?php echo e($dept); ?>"
                        <?php
                        echo (
                            $department === $dept
                            ? "selected"
                            : ""
                        );
                        ?>
                    >
                        <?php echo e($dept); ?>
                    </option>

                <?php } ?>

            </select>

            <select name="status">

                <option value="">
                    All statuses
                </option>

                <option
                    value="Active"
                    <?php echo $status === "Active" ? "selected" : ""; ?>
                >
                    Active
                </option>

                <option
                    value="Leave"
                    <?php echo $status === "Leave" ? "selected" : ""; ?>
                >
                    On Leave
                </option>

                <option
                    value="Inactive"
                    <?php echo $status === "Inactive" ? "selected" : ""; ?>
                >
                    Inactive
                </option>

            </select>

            <button
                type="submit"
                class="btn btn-light"
            >
                Filter
            </button>

            <?php if ($search !== "" || $department !== "" || $status !== "") { ?>

                <a
                    href="index.php"
                    class="btn btn-light"
                >
                    Clear
                </a>

            <?php } ?>

        </form>

    </div>

    <!-- EMPLOYEE TABLE -->

    <div class="employees-table-wrapper">

        <table class="ops-employees-table">

            <thead>

                <tr>

                    <th>
                        Employee
                    </th>

                    <th>
                        Department
                    </th>

                    <th>
                        Position
                    </th>

                    <th>
                        Hired
                    </th>

                    <th>
                        Salary
                    </th>

                    <th>
                        Status
                    </th>

                    <th>
                    </th>

                </tr>

            </thead>

            <tbody>

            <?php if (!empty($employees)) { ?>

                <?php foreach ($employees as $employee) { ?>

                    <tr>

                        <!-- Employee -->

                        <td>

                            <div class="employee-cell-new">

                                <div class="employee-avatar-new">

                                    <?php
                                    echo e(
                                        employeeInitials(
                                            $employee["fullname"]
                                        )
                                    );
                                    ?>

                                </div>

                                <div>

                                    <span class="employee-name-new">

                                        <?php
                                        echo e(
                                            $employee["fullname"]
                                        );
                                        ?>

                                    </span>

                                    <span class="employee-meta-new">

                                        <?php
                                        echo e(
                                            $employee["employee_number"]
                                        );
                                        ?>

                                        ·

                                        <?php
                                        echo e(
                                            $employee["email"]
                                        );
                                        ?>

                                    </span>

                                </div>

                            </div>

                        </td>

                        <!-- Department -->

                        <td>

                            <?php
                            echo e(
                                $employee["department"] ?: "—"
                            );
                            ?>

                        </td>

                        <!-- Position -->

                        <td>

                            <?php
                            echo e(
                                $employee["position"] ?: "—"
                            );
                            ?>

                        </td>

                        <!-- Hire date -->

                        <td>

                            <?php

                            if (
                                !empty(
                                    $employee["hire_date"]
                                )
                            ) {

                                $timestamp = strtotime(
                                    $employee["hire_date"]
                                );

                                echo $timestamp
                                    ? e(
                                        date(
                                            "d M Y",
                                            $timestamp
                                        )
                                    )
                                    : "—";

                            } else {

                                echo "—";

                            }

                            ?>

                        </td>

                        <!-- Salary -->

                        <td class="employee-money">

                            R
                            <?php
                            echo number_format(
                                (float)$employee["salary"],
                                0,
                                ".",
                                " "
                            );
                            ?>

                        </td>

                        <!-- Status -->

                        <td>

                            <span
                                class="badge <?php
                                    echo e(
                                        employeeStatusClass(
                                            $employee["status"]
                                        )
                                    );
                                ?>"
                            >

                                <?php

                                if (
                                    $employee["status"] === "Leave"
                                ) {

                                    echo "On Leave";

                                } else {

                                    echo e(
                                        $employee["status"]
                                    );

                                }

                                ?>

                            </span>

                        </td>

                        <!-- Actions -->

                        <td>

                            <div class="employee-actions-new">

                                <a
                                    class="employee-view"
                                    href="view.php?id=<?php echo (int)$employee["id"]; ?>"
                                    title="View employee"
                                >
                                    <i class="fa-regular fa-eye"></i>
                                </a>

                                <?php
                                if (
                                    isset($_SESSION["role"]) &&
                                    in_array(
                                        $_SESSION["role"],
                                        ["Admin", "Manager"],
                                        true
                                    )
                                ) {
                                ?>

                                    <a
                                        class="employee-edit"
                                        href="edit.php?id=<?php echo (int)$employee["id"]; ?>"
                                        title="Edit employee"
                                    >
                                        <i class="fa-solid fa-pen"></i>
                                    </a>

                                <?php } ?>

                                <?php
                                if (
                                    isset($_SESSION["role"]) &&
                                    $_SESSION["role"] === "Admin"
                                ) {
                                ?>

                                    <a
                                        class="employee-delete"
                                        href="delete.php?id=<?php echo (int)$employee["id"]; ?>"
                                        title="Delete employee"
                                        onclick="return confirm('Delete this employee?');"
                                    >
                                        <i class="fa-regular fa-trash-can"></i>
                                    </a>

                                <?php } ?>

                            </div>

                        </td>

                    </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>

                    <td
                        colspan="7"
                        class="employee-empty"
                    >

                        <strong>
                            No employees found
                        </strong>

                        <?php if ($search || $department || $status) { ?>

                            Try changing your search or filters.

                        <?php } else { ?>

                            No employees have been added yet.

                        <?php } ?>

                    </td>

                </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

</main>

</div>

</body>

</html>