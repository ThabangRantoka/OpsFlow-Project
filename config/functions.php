<?php
/**
 * ==========================================
 * OpsFlow Enterprise Management System
 * Common reusable functions
 * ==========================================
 */

/**
 * Total Employees
 */
function getTotalEmployees($conn)
{

    $sql = "SELECT COUNT(*) AS total FROM employees";

    $result = $conn->query($sql);

    $row = $result->fetch_assoc();


    return $row["total"];

}


/**
 * Total Projects
 */
function getTotalProjects($conn)
{

    $sql = "SELECT COUNT(*) AS total FROM projects";

    $result = $conn->query($sql);

    $row = $result->fetch_assoc();


    return $row["total"];

}


/**
 * Total Attendance
 */
function getTotalAttendance($conn)
{

    $sql = "SELECT COUNT(*) AS total FROM attendance";

    $result = $conn->query($sql);

    $row = $result->fetch_assoc();


    return $row["total"];

}


/**
 * Total Departments
 */
function getTotalDepartments($conn)
{

    $sql = "SELECT COUNT(*) AS total FROM departments";

    $result = $conn->query($sql);

    $row = $result->fetch_assoc();


    return $row["total"];

}


/**
 * Format Date
 */
function formatDate($date)
{

    return date("d M Y", strtotime($date));

}


/**
 * Escape HTML
 */
function e($value)
{

    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

}


/*
======================================
Create Notification
======================================
*/

function createNotification($conn, $arg2, $arg3, $arg4 = null, $arg5 = null)
{

    // Supports both broadcast notifications (title, message, icon) and
    // targeted notifications (userId, title, message, icon).
    if ($arg5 !== null) {

        $userId = (int)$arg2;

        $title = (string)$arg3;

        $message = (string)$arg4;

        $icon = (string)$arg5;

        $stmt = $conn->prepare("INSERT INTO notifications (user_id, title, message, icon) VALUES (?, ?, ?, ?)");

        if (!$stmt) return false;

        $stmt->bind_param("isss", $userId, $title, $message, $icon);

        return $stmt->execute();

    }


    $title = (string)$arg2;

    $message = (string)$arg3;

    $icon = $arg4 !== null ? (string)$arg4 : "🔔";

    $stmt = $conn->prepare("INSERT INTO notifications (user_id, title, message, icon) SELECT id, ?, ?, ? FROM users WHERE status='Active'");

    if (!$stmt) return false;

    $stmt->bind_param("sss", $title, $message, $icon);

    return $stmt->execute();

}


/*
======================================
Activity Log
======================================
*/

function logActivity($conn, $user_id, $fullname, $action)
{

    $stmt = $conn->prepare("
        INSERT INTO activity_logs
        (user_id, fullname, action)
        VALUES (?, ?, ?)
    ");


    $stmt->bind_param("iss", $user_id, $fullname, $action);


    return $stmt->execute();

}

