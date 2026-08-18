<?php

require_once("../config/auth.php");
require_once("../config/DataBase.php");
require_once("../config/permissions.php");

requireRole(["Admin", "Manager", "Employee"]);

/*
|--------------------------------------------------------------------------
| Current logged-in user
|--------------------------------------------------------------------------
*/

$userId = (int)($_SESSION["user_id"] ?? 0);

if ($userId <= 0) {
    header("Location: ../Login/index.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| Mark notification as read
|--------------------------------------------------------------------------
|
| Open:
| Notifications/index.php?read=ID
|
*/

if (isset($_GET["read"])) {

    $notificationId = (int)$_GET["read"];

    if ($notificationId > 0) {

        $stmt = $conn->prepare("
            UPDATE notifications
            SET is_read = 1
            WHERE id = ?
            AND user_id = ?
        ");

        if ($stmt) {
            $stmt->bind_param("ii", $notificationId, $userId);
            $stmt->execute();
            $stmt->close();
        }
    }

    header("Location: index.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| Mark all notifications as read
|--------------------------------------------------------------------------
*/

if (isset($_GET["read_all"])) {

    $stmt = $conn->prepare("
        UPDATE notifications
        SET is_read = 1
        WHERE user_id = ?
    ");

    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: index.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| Load notifications for current user
|--------------------------------------------------------------------------
*/

$notifications = [];

$stmt = $conn->prepare("
    SELECT
        id,
        title,
        message,
        icon,
        is_read,
        created_at
    FROM notifications
    WHERE user_id = ?
    ORDER BY created_at DESC, id DESC
");

if ($stmt) {

    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }

    $stmt->close();
}

/*
|--------------------------------------------------------------------------
| Count unread notifications
|--------------------------------------------------------------------------
*/

$unreadCount = 0;

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM notifications
    WHERE user_id = ?
    AND is_read = 0
");

if ($stmt) {

    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $countResult = $stmt->get_result()->fetch_assoc();

    $unreadCount = (int)($countResult["total"] ?? 0);

    $stmt->close();
}

/*
|--------------------------------------------------------------------------
| Helper functions
|--------------------------------------------------------------------------
*/

function notificationIcon($icon)
{
    $icon = trim((string)$icon);

    if ($icon === "") {
        return "fa-bell";
    }

    /*
     * Allow Font Awesome icon names.
     * Example:
     * fa-user
     * fa-calendar
     * fa-folder
     * fa-triangle-exclamation
     */

    if (strpos($icon, "fa-") === 0) {
        return $icon;
    }

    return "fa-bell";
}

function notificationTime($date)
{
    if (!$date) {
        return "—";
    }

    $timestamp = strtotime($date);

    if (!$timestamp) {
        return htmlspecialchars($date);
    }

    return date("d M Y, H:i", $timestamp);
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>OpsFlow | Notifications</title>

<link rel="stylesheet" href="../Assets/CSS/style.css">
<link rel="stylesheet" href="../Assets/CSS/dashboard.css">

<style>

.notifications-header {

    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 22px;

}

.notifications-title {

    display: flex;
    align-items: center;
    gap: 10px;

}

.notifications-title h1 {

    margin: 0;
    font-size: 24px;

}

.notification-count {

    display: inline-flex;
    align-items: center;
    justify-content: center;

    min-width: 24px;
    height: 24px;

    padding: 0 8px;

    border-radius: 999px;

    background: #e53935;
    color: white;

    font-size: 12px;
    font-weight: 700;

}

.notifications-actions {

    display: flex;
    gap: 10px;

}

.notifications-table {

    width: 100%;
    border-collapse: collapse;

}

.notifications-table th {

    text-align: left;

    padding: 14px 16px;

    background: #f5f7fa;

    color: #53657d;

    font-size: 12px;

    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: .03em;

}

.notifications-table td {

    padding: 17px 16px;

    border-bottom: 1px solid #edf0f4;

    vertical-align: middle;

}

.notification-row.unread {

    background: #f8fbff;

}

.notification-row.unread td:first-child {

    border-left: 3px solid #0ea5b7;

}

.notification-icon {

    width: 42px;
    height: 42px;

    border-radius: 12px;

    display: flex;

    align-items: center;
    justify-content: center;

    background: #e8f8fa;

    color: #0891a3;

}

.notification-title {

    display: block;

    font-weight: 700;

    color: #14233a;

    margin-bottom: 4px;

}

.notification-message {

    color: #64748b;

    font-size: 14px;

    line-height: 1.5;

}

.notification-date {

    color: #64748b;

    white-space: nowrap;

    font-size: 13px;

}

.notification-status {

    display: inline-flex;

    align-items: center;

    gap: 6px;

    font-size: 12px;

    font-weight: 700;

}

.notification-status.unread {

    color: #0e8fa0;

}

.notification-status.read {

    color: #94a3b8;

}

.notification-action {

    text-decoration: none;

    font-size: 13px;

    font-weight: 600;

    color: #1769aa;

}

.notification-action:hover {

    text-decoration: underline;

}

.empty-notifications {

    padding: 70px 20px;

    text-align: center;

}

.empty-notifications-icon {

    width: 64px;
    height: 64px;

    margin: 0 auto 16px;

    border-radius: 18px;

    display: flex;

    align-items: center;
    justify-content: center;

    background: #f1f5f9;

    color: #64748b;

    font-size: 26px;

}

.empty-notifications h3 {

    margin: 0 0 7px;

    color: #17243a;

}

.empty-notifications p {

    margin: 0;

    color: #64748b;

}

@media(max-width: 800px) {

    .notifications-header {

        align-items: flex-start;
        flex-direction: column;

    }

    .notifications-table {

        min-width: 750px;

    }

    .content-card {

        overflow-x: auto;

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

<h1>Notifications</h1>

<p>
You have <?php echo count($notifications); ?> notification<?php echo count($notifications) === 1 ? '' : 's'; ?>
<?php if ($unreadCount > 0) { ?>
 · <?php echo $unreadCount; ?> unread
<?php } ?>
</p>

</div>

<?php if ($unreadCount > 0) { ?>

<a
href="index.php?read_all=1"
class="btn btn-light"
>
<i class="fa-solid fa-check-double"></i>
Mark all as read
</a>

<?php } ?>

</div>

</section>

<div class="content-card">

<?php if (count($notifications) > 0) { ?>

<table class="notifications-table">

<thead>

<tr>

<th style="width:80px;">Icon</th>

<th>Title</th>

<th>Message</th>

<th>Date</th>

<th>Status</th>

<th></th>

</tr>

</thead>

<tbody>

<?php foreach ($notifications as $notification) { ?>

<?php

$isUnread = (int)$notification["is_read"] === 0;

$icon = notificationIcon($notification["icon"]);

?>

<tr class="notification-row <?php echo $isUnread ? 'unread' : ''; ?>">

<td>

<div class="notification-icon">

<i class="fa-solid <?php echo htmlspecialchars($icon); ?>"></i>

</div>

</td>

<td>

<span class="notification-title">

<?php echo htmlspecialchars($notification["title"]); ?>

</span>

</td>

<td>

<div class="notification-message">

<?php echo nl2br(htmlspecialchars($notification["message"])); ?>

</div>

</td>

<td>

<span class="notification-date">

<?php echo notificationTime($notification["created_at"]); ?>

</span>

</td>

<td>

<?php if ($isUnread) { ?>

<span class="notification-status unread">

<i class="fa-solid fa-circle"></i>
Unread

</span>

<?php } else { ?>

<span class="notification-status read">

<i class="fa-solid fa-check"></i>
Read

</span>

<?php } ?>

</td>

<td>

<?php if ($isUnread) { ?>

<a
href="index.php?read=<?php echo (int)$notification["id"]; ?>"
class="notification-action"
>
Mark read
</a>

<?php } ?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

<?php } else { ?>

<div class="empty-notifications">

<div class="empty-notifications-icon">

<i class="fa-regular fa-bell"></i>

</div>

<h3>No notifications yet</h3>

<p>
You're all caught up. New system notifications will appear here.
</p>

</div>

<?php } ?>

</div>

</main>

</div>

</body>

</html>