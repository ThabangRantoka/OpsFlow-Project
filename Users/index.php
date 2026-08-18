<?php

require_once("../config/auth.php");
require_once("../config/permissions.php");
require_once("../config/DataBase.php");

requireRole(["Admin"]);

/*
|--------------------------------------------------------------------------
| Get users
|--------------------------------------------------------------------------
*/

$users = $conn->query("
    SELECT
        id,
        fullname,
        email,
        created_at,
        role,
        status
    FROM users
    ORDER BY id DESC
");

/*
|--------------------------------------------------------------------------
| User initials
|--------------------------------------------------------------------------
*/

function userInitials($name)
{
    $name = trim($name);

    if ($name === "") {
        return "U";
    }

    $parts = preg_split('/\s+/', $name);

    $initials = "";

    foreach (array_slice($parts, 0, 2) as $part) {
        $initials .= strtoupper(substr($part, 0, 1));
    }

    return $initials ?: "U";
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

    <title>OpsFlow | Users & Roles</title>

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

</head>

<body>

<div class="dashboard">

    <?php include("../Includes/sidebar.php"); ?>

    <main class="main-content">

        <?php include("../Includes/header.php"); ?>


        <!-- PAGE HEADER -->

        <section class="page-heading">

            <div class="page-toolbar">

                <div>

                    <h1>
                        Users &amp; Roles
                    </h1>

                    <p>
                        Control who can view and change operational data
                    </p>

                </div>


                <div class="actions">

                    <a
                        href="add.php"
                        class="btn btn-primary"
                    >

                        <i class="fa-solid fa-plus"></i>

                        Add user

                    </a>

                </div>

            </div>

        </section>


        <!-- USERS TABLE -->

        <div class="content-card flush">

            <div class="table-responsive">

                <table class="employee-table">

                    <thead>

                        <tr>

                            <th>
                                User
                            </th>

                            <th>
                                Job title
                            </th>

                            <th>
                                Joined
                            </th>

                            <th>
                                Role
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php

                    if (
                        $users &&
                        $users->num_rows > 0
                    ) {

                        while (
                            $u = $users->fetch_assoc()
                        ) {

                    ?>

                        <tr>


                            <!-- USER -->

                            <td>

                                <div class="employee-cell">

                                    <div class="employee-avatar">

                                        <?php
                                        echo htmlspecialchars(
                                            userInitials(
                                                $u["fullname"] ?? ""
                                            )
                                        );
                                        ?>

                                    </div>


                                    <div>

                                        <span class="employee-main">

                                            <?php
                                            echo htmlspecialchars(
                                                $u["fullname"] ?? ""
                                            );
                                            ?>

                                        </span>


                                        <span class="employee-meta">

                                            <?php
                                            echo htmlspecialchars(
                                                $u["email"] ?? ""
                                            );
                                            ?>

                                        </span>

                                    </div>

                                </div>

                            </td>


                            <!-- JOB TITLE -->

                            <td>

                                —

                            </td>


                            <!-- JOINED -->

                            <td>

                                <?php

                                if (
                                    !empty($u["created_at"])
                                ) {

                                    echo date(
                                        "d M Y",
                                        strtotime(
                                            $u["created_at"]
                                        )
                                    );

                                } else {

                                    echo "—";

                                }

                                ?>

                            </td>


                            <!-- ROLE -->

                            <td>

                                <form
                                    action="update.php"
                                    method="POST"
                                    style="
                                        margin:0;
                                        display:inline-flex;
                                        gap:6px;
                                    "
                                >

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?php
                                            echo (int)$u["id"];
                                        ?>"
                                    >


                                    <select
                                        name="role"
                                        onchange="this.form.submit()"
                                        style="
                                            width:130px;
                                            height:38px;
                                            margin:0;
                                            padding:0 10px;
                                        "
                                    >

                                        <option
                                            value="Admin"
                                            <?php
                                            echo (
                                                $u["role"] === "Admin"
                                            )
                                            ? "selected"
                                            : "";
                                            ?>
                                        >
                                            Admin
                                        </option>


                                        <option
                                            value="Manager"
                                            <?php
                                            echo (
                                                $u["role"] === "Manager"
                                            )
                                            ? "selected"
                                            : "";
                                            ?>
                                        >
                                            Manager
                                        </option>


                                        <option
                                            value="Employee"
                                            <?php
                                            echo (
                                                $u["role"] === "Employee"
                                            )
                                            ? "selected"
                                            : "";
                                            ?>
                                        >
                                            Employee
                                        </option>

                                    </select>

                                </form>

                            </td>


                            <!-- STATUS -->

                            <td>

                                <span
                                    class="badge <?php

                                    echo (
                                        ($u["status"] ?? "")
                                        === "Active"
                                    )
                                    ? "active"
                                    : "inactive";

                                    ?>"
                                >

                                    <?php
                                    echo htmlspecialchars(
                                        $u["status"] ?? ""
                                    );
                                    ?>

                                </span>

                            </td>

                        </tr>


                    <?php

                        }

                    } else {

                    ?>

                        <tr>

                            <td colspan="5">

                                <div class="empty-state">

                                    <strong>
                                        No users found
                                    </strong>

                                    <span>
                                        Create the first user account.
                                    </span>

                                </div>

                            </td>

                        </tr>

                    <?php

                    }

                    ?>

                    </tbody>

                </table>

            </div>

        </div>

    </main>

</div>

</body>

</html>