<?php
/*
==========================================
OpsFlow Enterprise Management System
Role-Based Permissions
Developer: Moyahabo Thabang
Version: 2.0
==========================================
*/

function requireRole($roles)
{
    // User must be logged in
    if (!isset($_SESSION["user_id"])) {

        header("Location: ../Auth/login.php");
        exit();

    }

    // Allow a single role or multiple roles
    if (!is_array($roles)) {
        $roles = [$roles];
    }

    // Check permission
    if (!in_array($_SESSION["role"], $roles)) {

        http_response_code(403);

        die("
        <div style='
            max-width:600px;
            margin:100px auto;
            padding:30px;
            font-family:Arial,sans-serif;
            text-align:center;
            border:1px solid #ddd;
            border-radius:10px;
            box-shadow:0 2px 10px rgba(0,0,0,.1);
        '>

            <h1 style='color:#dc3545;'>403 - Access Denied</h1>

            <p>
                You do not have permission to access this page.
            </p>

            <br>

            <a href='../Dashboard/index.php'
               style='
               background:#2563eb;
               color:white;
               padding:10px 20px;
               text-decoration:none;
               border-radius:6px;'>
               Return to Dashboard
            </a>

        </div>
        ");

    }
}