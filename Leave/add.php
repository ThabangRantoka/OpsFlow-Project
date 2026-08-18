<?php
/*
==========================================
OpsFlow Enterprise Management System
Leave Request
Developer: Moyahabo Thabang
Version : 1.0
==========================================
*/

require_once("../config/auth.php");
require_once("../config/permissions.php");
require_once("../config/DataBase.php");

requireRole(["Employee"]);

$error = trim($_GET["error"] ?? $_GET["msg"] ?? "");

// Get logged-in employee

$stmt = $conn->prepare("
SELECT id, fullname
FROM employees
WHERE email=?
LIMIT 1
");

$stmt->bind_param("s", $_SESSION["email"]);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows==0){

die("Employee record not found.");

}

$employee = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Request Leave</title>

<link rel="stylesheet" href="../Assets/CSS/style.css">
<link rel="stylesheet" href="../Assets/CSS/dashboard.css">

</head>

<body>

<div class="dashboard">

<?php include("../Includes/sidebar.php"); ?>

<div class="main-content">

<?php include("../Includes/header.php"); ?>

<div class="recent">

<?php if ($error !== "") { ?><div class="error-state" style="margin-bottom:18px;padding:14px 16px;border-radius:10px;background:#fff0f1;color:#c63d4c;border:1px solid #f4c6cb;"><?php echo htmlspecialchars($error); ?></div><?php } ?>

<h2>🏖 Request Leave</h2>

<br>

<form action="save.php" method="POST">

<p>Submit a request for <strong><?php echo htmlspecialchars($employee["fullname"]); ?></strong>.</p>

<label>Employee</label>

<input
type="text"
value="<?php echo htmlspecialchars($employee["fullname"]); ?>"
readonly>

<br>

<label>Leave Type</label>

<select name="leave_type" required>

<option value="">-- Select Leave Type --</option>

<option>Annual</option>

<option>Sick</option>

<option>Maternity</option>

<option>Paternity</option>

<option>Study</option>

<option>Unpaid</option>

<option>Other</option>

</select>

<br>

<label>Start Date</label>

<input
type="date"
name="start_date"
required>

<br>

<label>End Date</label>

<input
type="date"
name="end_date"
required>

<br>

<label>Reason</label>

<textarea
name="reason"
rows="5"
required></textarea>

<br>

<button type="submit">

Submit Leave Request

</button>

<a
href="index.php"
class="btn btn-primary">

⬅ Cancel

</a>

</form>

</div>

</div>

</div>

</body>

</html>