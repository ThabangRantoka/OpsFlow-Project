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
Ensure Employee Account Link
======================================
*/

function ensureEmployeeRecord($conn, $userId, $fullname, $email, $phone = "")
{
    $userId = (int)$userId;

    $checkUser = $conn->prepare("SELECT employee_id FROM users WHERE id=? LIMIT 1");

    if (!$checkUser) {
        return false;
    }

    $checkUser->bind_param("i", $userId);
    $checkUser->execute();
    $userResult = $checkUser->get_result();
    $user = $userResult->fetch_assoc();
    $checkUser->close();

    if (!$user) {
        return false;
    }

    if (!empty($user["employee_id"])) {
        $employeeId = (int)$user["employee_id"];

        $checkEmployee = $conn->prepare("SELECT id FROM employees WHERE id=? LIMIT 1");

        if ($checkEmployee) {
            $checkEmployee->bind_param("i", $employeeId);
            $checkEmployee->execute();
            $exists = $checkEmployee->get_result()->num_rows > 0;
            $checkEmployee->close();

            if ($exists) {
                return $employeeId;
            }
        }
    }

    $checkEmail = $conn->prepare("SELECT id FROM employees WHERE email=? LIMIT 1");

    if (!$checkEmail) {
        return false;
    }

    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $employee = $checkEmail->get_result()->fetch_assoc();
    $checkEmail->close();

    if ($employee) {
        $employeeId = (int)$employee["id"];

        $updateUser = $conn->prepare("UPDATE users SET employee_id=? WHERE id=?");

        if (!$updateUser) {
            return false;
        }

        $updateUser->bind_param("ii", $employeeId, $userId);
        $success = $updateUser->execute();
        $updateUser->close();

        return $success ? $employeeId : false;
    }

    $numberResult = $conn->query("\n        SELECT MAX(CAST(SUBSTRING(employee_number, 4) AS UNSIGNED)) AS max_number\n        FROM employees\n        WHERE employee_number REGEXP '^EMP[0-9]+$'\n    ");

    if (!$numberResult) {
        return false;
    }

    $numberRow = $numberResult->fetch_assoc();
    $nextNumber = ((int)($numberRow["max_number"] ?? 0)) + 1;
    $employeeNumber = "EMP" . str_pad((string)$nextNumber, 3, "0", STR_PAD_LEFT);
    $employeeStatus = "Active";
    $position = "Employee";
    $salary = null;
    $hireDate = null;
    $gender = null;
    $department = null;

    $insertEmployee = $conn->prepare("\n        INSERT INTO employees\n        (employee_number, fullname, gender, email, phone, department, position, salary, hire_date, status)\n        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)\n    ");

    if (!$insertEmployee) {
        return false;
    }

    $insertEmployee->bind_param(
        "ssssssssss",
        $employeeNumber,
        $fullname,
        $gender,
        $email,
        $phone,
        $department,
        $position,
        $salary,
        $hireDate,
        $employeeStatus
    );

    if (!$insertEmployee->execute()) {
        $insertEmployee->close();
        return false;
    }

    $employeeId = (int)$insertEmployee->insert_id;
    $insertEmployee->close();

    $updateUser = $conn->prepare("UPDATE users SET employee_id=? WHERE id=?");

    if (!$updateUser) {
        return false;
    }

    $updateUser->bind_param("ii", $employeeId, $userId);
    $success = $updateUser->execute();
    $updateUser->close();

    return $success ? $employeeId : false;
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
