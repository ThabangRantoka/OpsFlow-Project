<?php
/**
 * ==========================================================
 * OpsFlow Enterprise Management System
 * File: Projects/index.php
 * Description: Projects listing
 * ==========================================================
 */

require_once("../config/auth.php");

require_once("../config/DataBase.php");

require_once("../config/permissions.php");


requireRole(["Admin", "Manager", "Employee"]);



/*
|--------------------------------------------------------------------------
| Fetch projects
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        id,
        project_code,
        project_name,
        department,
        project_manager,
        start_date,
        end_date,
        budget,
        progress,
        priority,
        status,
        created_at
    FROM projects
    ORDER BY id DESC
";


$result = $conn->query($sql);


if ($result === false) {

    die("Projects query failed: " . htmlspecialchars($conn->error));

}


$projectCount = $result->num_rows;



/*
|--------------------------------------------------------------------------
| Helper functions
|--------------------------------------------------------------------------
*/

function priorityClass($priority)
{

    switch ($priority) {

        case 'Low':
            return 'priority-low';


        case 'Medium':
            return 'priority-medium';


        case 'High':
            return 'priority-high';


        case 'Critical':
            return 'priority-critical';


        default:
            return 'priority-medium';

    }

}



function projectStatusClass($status)
{

    switch ($status) {

        case 'Planning':
            return 'status-planning';


        case 'In Progress':
            return 'status-progress';


        case 'Completed':
            return 'status-completed';


        case 'On Hold':
            return 'status-hold';


        default:
            return 'status-planning';

    }

}



function formatProjectDate($date)
{

    if (empty($date)) {

        return '—';

    }


    $timestamp = strtotime($date);


    if ($timestamp === false) {

        return '—';

    }


    return date('d M Y', $timestamp);

}



function formatMoney($amount)
{

    return 'R ' . number_format((float)$amount, 0, '.', ' ');

}



function progressValue($progress)
{

    $progress = (int)$progress;


    if ($progress < 0) {

        return 0;

    }


    if ($progress > 100) {

        return 100;

    }


    return $progress;

}


?>

<!DOCTYPE html>

<html lang="en">


<head>


    <meta charset="UTF-8">


    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >


    <title>OpsFlow | Projects</title>


    <link
        rel="stylesheet"
        href="../Assets/CSS/style.css?v=2"
    >


    <link
        rel="stylesheet"
        href="../Assets/CSS/dashboard.css?v=2"
    >


    <!--
    |--------------------------------------------------------------------------
    | Projects-specific styling
    |--------------------------------------------------------------------------
    | This is intentionally included here so existing CSS cannot hide the
    | project cards.
    |--------------------------------------------------------------------------
    -->


    <style>

        .projects-page {
            width: 100%;
        }

        .projects-page .page-heading {
            margin-bottom: 24px;
        }

        .projects-page .page-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .projects-page .page-toolbar h1 {
            margin: 0;
        }

        .projects-page .page-toolbar p {
            margin: 6px 0 0;
        }

        .projects-page .project-grid {
            width: 100%;
            display: grid !important;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
            align-items: start;
        }

        .projects-page .project-card {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            width: 100%;
            min-width: 0;
            box-sizing: border-box;

            background: #ffffff;
            border: 1px solid #e3e9ef;
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 8px 25px rgba(15, 23, 42, 0.06);

            color: #142033;
        }

        .projects-page .project-card:hover {
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.10);
            transform: translateY(-1px);
        }

        .projects-page .project-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
        }

        .projects-page .project-title {
            margin: 0 0 6px;
            font-size: 19px;
            line-height: 1.3;
            font-weight: 700;
            color: #111827;
        }

        .projects-page .project-code {
            color: #64748b;
            font-size: 13px;
            line-height: 1.5;
        }

        .projects-page .project-badges {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 7px;
        }

        .projects-page .pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 28px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        /* Priority */

        .projects-page .priority-low {
            color: #64748b;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
        }

        .projects-page .priority-medium {
            color: #1681bd;
            background: #eff8fd;
            border: 1px solid #bfe2f5;
        }

        .projects-page .priority-high {
            color: #b7791f;
            background: #fff8e7;
            border: 1px solid #f4d28b;
        }

        .projects-page .priority-critical {
            color: #dc3545;
            background: #fff0f2;
            border: 1px solid #ffc5cc;
        }

        /* Status */

        .projects-page .status-planning,
        .projects-page .status-progress {
            color: #1681bd;
            background: #eff8fd;
            border: 1px solid #bfe2f5;
        }

        .projects-page .status-completed {
            color: #17855b;
            background: #effaf5;
            border: 1px solid #bfe8d3;
        }

        .projects-page .status-hold {
            color: #9a6700;
            background: #fff8e7;
            border: 1px solid #f1d28c;
        }

        .projects-page .project-progress-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 24px;
            margin-bottom: 8px;

            color: #64748b;
            font-size: 13px;
        }

        .projects-page .project-progress-label span:last-child {
            font-weight: 600;
            color: #64748b;
        }

        .projects-page .progress-track {
            width: 100%;
            height: 8px;
            overflow: hidden;
            background: #dcecf4;
            border-radius: 999px;
        }

        .projects-page .progress-fill {
            display: block;
            height: 100%;
            min-width: 0;
            background: #1681bd;
            border-radius: 999px;
        }

        .projects-page .project-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            margin-top: 20px;
        }

        .projects-page .project-dates {
            color: #64748b;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .projects-page .project-budget {
            color: #111827;
            font-size: 16px;
            font-weight: 700;
        }

        .projects-page .project-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .projects-page .project-actions a {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            width: 34px;
            height: 34px;

            text-decoration: none;
            border-radius: 8px;
        }

        .projects-page .project-actions .view {
            color: #64748b;
        }

        .projects-page .project-actions .edit {
            color: #142033;
        }

        .projects-page .project-actions .delete {
            color: #ef3340;
        }

        .projects-page .project-actions a:hover {
            background: #f1f5f9;
        }

        .projects-page .empty-projects {
            grid-column: 1 / -1;

            background: #ffffff;
            border: 1px solid #e3e9ef;
            border-radius: 16px;
            padding: 50px 30px;
            text-align: center;
            color: #64748b;
        }

        .projects-page .empty-projects strong {
            display: block;
            margin-bottom: 8px;
            color: #111827;
            font-size: 17px;
        }

        .projects-page .database-error {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #b91c1c;
            padding: 16px;
            border-radius: 10px;
        }

        @media (max-width: 900px) {

            .projects-page .project-grid {
                grid-template-columns: 1fr;
            }

            .projects-page .page-toolbar {
                flex-direction: column;
                align-items: flex-start;
            }

        }

        @media (max-width: 600px) {

            .projects-page .project-top {
                flex-direction: column;
            }

            .projects-page .project-badges {
                justify-content: flex-start;
            }

            .projects-page .project-footer {
                flex-direction: column;
                align-items: flex-start;
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


        <div class="projects-page">


            <section class="page-heading">


                <div class="page-toolbar">


                    <div>


                        <h1>Projects</h1>


                        <p>

                            
<?php echo $projectCount;
 
?>
                            
<?php echo $projectCount === 1 ? 'project' : 'projects';
 
?>
                            in the delivery pipeline
                        </p>


                    </div>



                    <div class="actions">


                        
<?php
                        if (
                            isset($_SESSION['role']) &&
                            in_array(
                                $_SESSION['role'],
                                ['Admin', 'Manager'],
                                true
                            )
                        ):
                        
?>

                            <a
                                href="add.php"
                                class="btn btn-primary"
                            >

                                <i class="fa-solid fa-plus">
</i>
                                New project
                            </a>


                        
<?php endif;
 
?>

                    </div>


                </div>


            </section>



            <section class="project-grid">


                
<?php if ($projectCount > 0): 
?>

                    
<?php while ($p = $result->fetch_assoc()): 
?>

                        
<?php
                        $progress = progressValue($p['progress']);

                        $priorityClass = priorityClass($p['priority']);

                        $statusClass = projectStatusClass($p['status']);

                        
?>

                        <article class="project-card">


                            <div class="project-top">


                                <div>


                                    <h2 class="project-title">

                                        
<?php
                                        echo htmlspecialchars(
                                            $p['project_name'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );

                                        
?>
                                    </h2>


                                    <div class="project-code">


                                        
<?php
                                        echo htmlspecialchars(
                                            $p['project_code'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );

                                        
?>

                                        ·

                                        
<?php
                                        $manager = trim(
                                            (string)$p['project_manager']
                                        );


                                        echo htmlspecialchars(
                                            $manager !== ''
                                                ? $manager
                                                : 'Internal',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );

                                        
?>

                                    </div>


                                </div>



                                <div class="project-badges">


                                    <span class="pill 
<?php echo $priorityClass;
 
?>">

                                        
<?php
                                        echo htmlspecialchars(
                                            $p['priority'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );

                                        
?>
                                    </span>



                                    <span class="pill 
<?php echo $statusClass;
 
?>">

                                        
<?php
                                        echo htmlspecialchars(
                                            $p['status'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );

                                        
?>
                                    </span>


                                </div>


                            </div>



                            <div class="project-progress-label">


                                <span>Progress</span>


                                <span>

                                    
<?php echo $progress;
 
?>%
                                </span>


                            </div>



                            <div class="progress-track">


                                <div
                                    class="progress-fill"
                                    style="width: 
<?php echo $progress;
 
?>%;"
                                >
</div>


                            </div>



                            <div class="project-footer">


                                <div>


                                    <div class="project-dates">


                                        
<?php
                                        echo formatProjectDate(
                                            $p['start_date']
                                        );

                                        
?>

                                        →

                                        
<?php
                                        echo formatProjectDate(
                                            $p['end_date']
                                        );

                                        
?>

                                    </div>



                                    <div class="project-budget">


                                        
<?php
                                        echo formatMoney($p['budget']);

                                        
?>

                                    </div>


                                </div>



                                <div class="project-actions">


                                    <a
                                        href="view.php?id=
<?php echo (int)$p['id'];
 
?>"
                                        class="view"
                                        title="View project"
                                    >

                                        <i class="fa-regular fa-eye">
</i>

                                    </a>



                                    
<?php
                                    if (
                                        isset($_SESSION['role']) &&
                                        in_array(
                                            $_SESSION['role'],
                                            ['Admin', 'Manager'],
                                            true
                                        )
                                    ):
                                    
?>

                                        <a
                                            href="edit.php?id=
<?php echo (int)$p['id'];
 
?>"
                                            class="edit"
                                            title="Edit project"
                                        >

                                            <i class="fa-solid fa-pen">
</i>

                                        </a>


                                    
<?php endif;
 
?>


                                    
<?php
                                    if (
                                        isset($_SESSION['role']) &&
                                        $_SESSION['role'] === 'Admin'
                                    ):
                                    
?>

                                        <a
                                            href="delete.php?id=
<?php echo (int)$p['id'];
 
?>"
                                            class="delete"
                                            title="Delete project"
                                            onclick="return confirm('Delete this project?');"
                                        >

                                            <i class="fa-regular fa-trash-can">
</i>

                                        </a>


                                    
<?php endif;
 
?>

                                </div>


                            </div>


                        </article>


                    
<?php endwhile;
 
?>

                
<?php else: 
?>

                    <div class="empty-projects">


                        <strong>No projects found</strong>

                        Create a project to start your delivery pipeline.

                    </div>


                
<?php endif;
 
?>

            </section>


        </div>


    </main>


</div>


</body>

</html>