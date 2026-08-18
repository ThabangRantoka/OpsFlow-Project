<?php
/**
 * ==========================================================
 * OpsFlow Enterprise Management System
 * File: Projects/add.php
 * Description: Add New Project
 * ==========================================================
 */

require_once("../config/auth.php");
require_once("../config/DataBase.php");
require_once("../config/permissions.php");

requireRole(["Admin", "Manager"]);


/*
|--------------------------------------------------------------------------
| Get departments
|--------------------------------------------------------------------------
*/

$departments = $conn->query("
    SELECT department_name
    FROM departments
    ORDER BY department_name ASC
");

if ($departments === false) {
    die(
        "Unable to load departments: " .
        htmlspecialchars($conn->error)
    );
}


/*
|--------------------------------------------------------------------------
| Messages
|--------------------------------------------------------------------------
*/

$message = trim($_GET['msg'] ?? '');
$messageType = trim($_GET['type'] ?? '');


/*
|--------------------------------------------------------------------------
| Old values
|--------------------------------------------------------------------------
*/

$oldProjectCode = htmlspecialchars(
    $_GET['project_code'] ?? '',
    ENT_QUOTES,
    'UTF-8'
);

$oldProjectName = htmlspecialchars(
    $_GET['project_name'] ?? '',
    ENT_QUOTES,
    'UTF-8'
);

$oldDepartment = htmlspecialchars(
    $_GET['department'] ?? '',
    ENT_QUOTES,
    'UTF-8'
);

$oldManager = htmlspecialchars(
    $_GET['project_manager'] ?? '',
    ENT_QUOTES,
    'UTF-8'
);

$oldStartDate = htmlspecialchars(
    $_GET['start_date'] ?? '',
    ENT_QUOTES,
    'UTF-8'
);

$oldEndDate = htmlspecialchars(
    $_GET['end_date'] ?? '',
    ENT_QUOTES,
    'UTF-8'
);

$oldBudget = htmlspecialchars(
    $_GET['budget'] ?? '',
    ENT_QUOTES,
    'UTF-8'
);

$oldProgress = isset($_GET['progress'])
    ? (int)$_GET['progress']
    : 0;

$oldPriority = $_GET['priority'] ?? 'Medium';

$oldStatus = $_GET['status'] ?? 'Planning';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>OpsFlow | Add Project</title>

    <link
        rel="stylesheet"
        href="../Assets/CSS/style.css?v=2"
    >

    <link
        rel="stylesheet"
        href="../Assets/CSS/dashboard.css?v=2"
    >

    <style>

        .project-form-page {
            max-width: 950px;
        }

        .project-form-card {
            background: #ffffff;
            border: 1px solid #e3e9ef;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(15, 23, 42, 0.06);
        }

        .project-form-title {
            margin: 0 0 6px;
            font-size: 22px;
            color: #111827;
        }

        .project-form-subtitle {
            margin: 0 0 28px;
            color: #64748b;
        }

        .project-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .project-form-group {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .project-form-group.full {
            grid-column: 1 / -1;
        }

        .project-form-group label {
            font-size: 14px;
            font-weight: 600;
            color: #334155;
        }

        .project-form-group input,
        .project-form-group select {
            width: 100%;
            min-height: 46px;
            box-sizing: border-box;

            border: 1px solid #d7e0e8;
            border-radius: 9px;
            padding: 0 13px;

            background: #ffffff;
            color: #111827;
            font-size: 14px;
            outline: none;
        }

        .project-form-group input:focus,
        .project-form-group select:focus {
            border-color: #1681bd;
            box-shadow: 0 0 0 3px rgba(22, 129, 189, 0.10);
        }

        .project-form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 28px;
            padding-top: 22px;
            border-top: 1px solid #e8edf2;
        }

        .form-alert {
            margin-bottom: 22px;
            padding: 13px 15px;
            border-radius: 9px;
            font-size: 14px;
        }

        .form-alert.error {
            color: #b91c1c;
            background: #fff1f2;
            border: 1px solid #fecdd3;
        }

        .form-alert.success {
            color: #166534;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
        }

        @media (max-width: 700px) {

            .project-form-grid {
                grid-template-columns: 1fr;
            }

            .project-form-group.full {
                grid-column: auto;
            }

            .project-form-card {
                padding: 20px;
            }

        }

    </style>

</head>


<body>

<div class="dashboard">

    <?php include("../Includes/sidebar.php"); ?>


    <main class="main-content">

        <?php include("../Includes/header.php"); ?>


        <div class="project-form-page">

            <section class="page-heading">

                <div class="page-toolbar">

                    <div>

                        <h1>Add New Project</h1>

                        <p>
                            Create a new project for the delivery pipeline.
                        </p>

                    </div>

                </div>

            </section>


            <div class="project-form-card">

                <?php if ($message !== ''): ?>

                    <div
                        class="form-alert <?php echo $messageType === 'success' ? 'success' : 'error'; ?>"
                    >
                        <?php echo htmlspecialchars($message); ?>
                    </div>

                <?php endif; ?>


                <h2 class="project-form-title">
                    Project details
                </h2>

                <p class="project-form-subtitle">
                    Enter the project information below.
                </p>


                <form
                    action="save.php"
                    method="POST"
                    autocomplete="off"
                >

                    <div class="project-form-grid">


                        <!-- Project Code -->

                        <div class="project-form-group">

                            <label for="project_code">
                                Project Code
                            </label>

                            <input
                                type="text"
                                id="project_code"
                                name="project_code"
                                placeholder="e.g. PRJ-006"
                                maxlength="30"
                                value="<?php echo $oldProjectCode; ?>"
                                required
                            >

                        </div>


                        <!-- Project Name -->

                        <div class="project-form-group">

                            <label for="project_name">
                                Project Name
                            </label>

                            <input
                                type="text"
                                id="project_name"
                                name="project_name"
                                placeholder="Project name"
                                maxlength="150"
                                value="<?php echo $oldProjectName; ?>"
                                required
                            >

                        </div>


                        <!-- Department -->

                        <div class="project-form-group">

                            <label for="department">
                                Department
                            </label>

                            <select
                                id="department"
                                name="department"
                                required
                            >

                                <option value="">
                                    -- Select Department --
                                </option>

                                <?php while ($dept = $departments->fetch_assoc()): ?>

                                    <?php
                                    $departmentName =
                                        $dept['department_name'];
                                    ?>

                                    <option
                                        value="<?php echo htmlspecialchars($departmentName, ENT_QUOTES, 'UTF-8'); ?>"
                                        <?php
                                        echo $oldDepartment === $departmentName
                                            ? 'selected'
                                            : '';
                                        ?>
                                    >
                                        <?php
                                        echo htmlspecialchars(
                                            $departmentName,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                        ?>
                                    </option>

                                <?php endwhile; ?>

                            </select>

                        </div>


                        <!-- Project Manager -->

                        <div class="project-form-group">

                            <label for="project_manager">
                                Project Manager
                            </label>

                            <input
                                type="text"
                                id="project_manager"
                                name="project_manager"
                                placeholder="Project manager"
                                maxlength="150"
                                value="<?php echo $oldManager; ?>"
                            >

                        </div>


                        <!-- Start Date -->

                        <div class="project-form-group">

                            <label for="start_date">
                                Start Date
                            </label>

                            <input
                                type="date"
                                id="start_date"
                                name="start_date"
                                value="<?php echo $oldStartDate; ?>"
                            >

                        </div>


                        <!-- End Date -->

                        <div class="project-form-group">

                            <label for="end_date">
                                End Date
                            </label>

                            <input
                                type="date"
                                id="end_date"
                                name="end_date"
                                value="<?php echo $oldEndDate; ?>"
                            >

                        </div>


                        <!-- Budget -->

                        <div class="project-form-group">

                            <label for="budget">
                                Project Budget
                            </label>

                            <input
                                type="number"
                                id="budget"
                                name="budget"
                                min="0"
                                step="0.01"
                                placeholder="0.00"
                                value="<?php echo $oldBudget; ?>"
                                required
                            >

                        </div>


                        <!-- Progress -->

                        <div class="project-form-group">

                            <label for="progress">
                                Progress (%)
                            </label>

                            <input
                                type="number"
                                id="progress"
                                name="progress"
                                min="0"
                                max="100"
                                value="<?php echo $oldProgress; ?>"
                            >

                        </div>


                        <!-- Priority -->

                        <div class="project-form-group">

                            <label for="priority">
                                Priority
                            </label>

                            <select
                                id="priority"
                                name="priority"
                            >

                                <option
                                    value="Low"
                                    <?php echo $oldPriority === 'Low' ? 'selected' : ''; ?>
                                >
                                    Low
                                </option>

                                <option
                                    value="Medium"
                                    <?php echo $oldPriority === 'Medium' ? 'selected' : ''; ?>
                                >
                                    Medium
                                </option>

                                <option
                                    value="High"
                                    <?php echo $oldPriority === 'High' ? 'selected' : ''; ?>
                                >
                                    High
                                </option>

                                <option
                                    value="Critical"
                                    <?php echo $oldPriority === 'Critical' ? 'selected' : ''; ?>
                                >
                                    Critical
                                </option>

                            </select>

                        </div>


                        <!-- Status -->

                        <div class="project-form-group">

                            <label for="status">
                                Status
                            </label>

                            <select
                                id="status"
                                name="status"
                            >

                                <option
                                    value="Planning"
                                    <?php echo $oldStatus === 'Planning' ? 'selected' : ''; ?>
                                >
                                    Planning
                                </option>

                                <option
                                    value="In Progress"
                                    <?php echo $oldStatus === 'In Progress' ? 'selected' : ''; ?>
                                >
                                    In Progress
                                </option>

                                <option
                                    value="Completed"
                                    <?php echo $oldStatus === 'Completed' ? 'selected' : ''; ?>
                                >
                                    Completed
                                </option>

                                <option
                                    value="On Hold"
                                    <?php echo $oldStatus === 'On Hold' ? 'selected' : ''; ?>
                                >
                                    On Hold
                                </option>

                            </select>

                        </div>


                    </div>


                    <div class="project-form-actions">

                        <a
                            href="index.php"
                            class="btn btn-light"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            <i class="fa-solid fa-floppy-disk"></i>
                            Save Project
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </main>

</div>

</body>
</html>