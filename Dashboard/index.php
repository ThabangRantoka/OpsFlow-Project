<?php
require_once("../config/auth.php");
require_once("../config/DataBase.php");
require_once("../config/functions.php");
require_once("../config/permissions.php");
requireRole(["Admin","Manager","Employee"]);

$totalEmployees = (int)getTotalEmployees($conn);
$totalProjects = (int)getTotalProjects($conn);
$totalDepartments = (int)getTotalDepartments($conn);
$activeEmployees = (int)$conn->query("SELECT COUNT(*) total FROM employees WHERE status='Active'")->fetch_assoc()["total"];
$openProjects = (int)$conn->query("SELECT COUNT(*) total FROM projects WHERE status IN ('Planning','In Progress','On Hold')")->fetch_assoc()["total"];
$completedProjects = (int)$conn->query("SELECT COUNT(*) total FROM projects WHERE status='Completed'")->fetch_assoc()["total"];
$inProgressProjects = (int)$conn->query("SELECT COUNT(*) total FROM projects WHERE status='In Progress'")->fetch_assoc()["total"];
$planningProjects = (int)$conn->query("SELECT COUNT(*) total FROM projects WHERE status='Planning'")->fetch_assoc()["total"];
$monthlyPayroll = (float)$conn->query("SELECT COALESCE(SUM(salary),0) total FROM employees WHERE status='Active'")->fetch_assoc()["total"];
$attendanceToday = (int)$conn->query("SELECT COUNT(DISTINCT employee_id) total FROM attendance WHERE attendance_date=CURDATE() AND status IN ('Present','Late')")->fetch_assoc()["total"];
$attendancePercent = $activeEmployees > 0 ? min(100, round(($attendanceToday / $activeEmployees) * 100)) : 0;

$deptLabels=[]; $deptValues=[];
$deptResult=$conn->query("SELECT d.department_name, COUNT(e.id) total FROM departments d LEFT JOIN employees e ON e.department=d.department_name OR e.department=d.department_code GROUP BY d.id ORDER BY total DESC, d.department_name ASC");
while($r=$deptResult->fetch_assoc()){ $deptLabels[]=$r["department_name"]; $deptValues[]=(int)$r["total"]; }

$projects = [];
$projectResult = $conn->query("SELECT id, project_code, project_name, start_date, end_date, budget, progress, status FROM projects ORDER BY id DESC LIMIT 5");
while($r=$projectResult->fetch_assoc()) $projects[]=$r;

$leaveRequests=[];
$leaveResult=$conn->query("SELECT lr.id, lr.leave_type, lr.status, e.fullname FROM leave_requests lr INNER JOIN employees e ON e.id=lr.employee_id ORDER BY lr.id DESC LIMIT 4");
while($r=$leaveResult->fetch_assoc()) $leaveRequests[]=$r;
$pendingLeave=(int)$conn->query("SELECT COUNT(*) total FROM leave_requests WHERE status='Pending'")->fetch_assoc()["total"];

$activity=[];
$activityResult=$conn->query("SELECT fullname, action, created_at FROM activity_logs ORDER BY id DESC LIMIT 4");
while($r=$activityResult->fetch_assoc()) $activity[]=$r;

function dashboardStatusClass($status){
    return $status === 'Completed' ? 'pill completed' : ($status === 'Planning' ? 'pill planning' : ($status === 'On Hold' ? 'pill hold' : 'pill progress'));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>OpsFlow | Dashboard</title>
<link rel="stylesheet" href="../Assets/CSS/style.css"><link rel="stylesheet" href="../Assets/CSS/dashboard.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="dashboard">
<?php include("../Includes/sidebar.php"); ?>
<main class="main-content">
<?php include("../Includes/header.php"); ?>

<section class="page-heading"><h1>Operations dashboard</h1><p>Live snapshot for <?php echo date("d M Y"); ?></p></section>

<section class="cards">
<div class="card"><div class="stat-icon people"><i class="fa-solid fa-users"></i></div><h3>Active employees</h3><h1><?php echo $activeEmployees; ?></h1><p><?php echo $totalEmployees; ?> total on record</p></div>
<div class="card"><div class="stat-icon projects"><i class="fa-regular fa-folder-open"></i></div><h3>Open projects</h3><h1><?php echo $openProjects; ?></h1><p><?php echo $totalProjects; ?> total projects</p></div>
<div class="card"><div class="stat-icon attendance"><i class="fa-regular fa-calendar-check"></i></div><h3>Attendance today</h3><h1><?php echo $attendancePercent; ?>%</h1><p><?php echo $attendanceToday; ?> of <?php echo $activeEmployees; ?> checked in</p></div>
<div class="card"><div class="stat-icon payroll"><i class="fa-solid fa-arrow-trend-up"></i></div><h3>Monthly payroll</h3><h1>R <?php echo number_format($monthlyPayroll,0,'.',' '); ?></h1><p><?php echo $totalDepartments; ?> departments</p></div>
</section>

<section class="dashboard-grid">
<div class="panel"><h2>Headcount by department</h2><div class="chart-wrap"><canvas id="headcountChart"></canvas></div></div>
<div class="panel"><h2>Project status mix</h2><div class="donut-wrap"><canvas id="projectChart"></canvas></div><div class="chart-legend"><span><i class="legend-dot legend-planning"></i>Planning</span><span><i class="legend-dot legend-progress"></i>In Progress</span><span><i class="legend-dot legend-completed"></i>Completed</span></div></div>
</section>

<section class="dashboard-grid" style="grid-template-columns:2fr 1fr;align-items:start">
<div class="panel"><div class="page-toolbar" style="margin-bottom:8px"><div><h2 style="margin:0">Project delivery</h2></div><a href="../Projects/index.php" class="btn btn-light">View all</a></div>
<?php if(!$projects){ ?><div class="empty-state"><strong>No projects yet</strong>Create a project to see delivery progress here.</div><?php } else { foreach($projects as $p){ ?>
<div style="margin:18px 0"><div style="display:flex;justify-content:space-between;gap:12px"><div><div style="font-weight:600;color:#17263a"><?php echo htmlspecialchars($p['project_name']); ?></div><div class="muted small"><?php echo htmlspecialchars($p['project_code']); ?> · due <?php echo $p['end_date'] ? date('d M Y',strtotime($p['end_date'])) : '—'; ?> · R <?php echo number_format((float)$p['budget'],0,'.',' '); ?></div></div><span class="pill <?php echo strtolower(str_replace(' ','-',$p['status'])); ?>"><?php echo htmlspecialchars($p['status']); ?></span></div><div class="progress-track" style="margin-top:9px"><div class="progress-fill" style="width:<?php echo max(0,min(100,(int)$p['progress'])); ?>%"></div></div></div>
<?php }} ?></div>

<div>
<div class="panel"><h2>Leave requests</h2><p class="muted"><?php echo $pendingLeave; ?> awaiting a decision</p>
<?php if(!$leaveRequests){ ?><div class="empty-state"><strong>No leave requests</strong></div><?php } else { foreach($leaveRequests as $l){ $cls=$l['status']==='Approved'?'approved':($l['status']==='Rejected'?'rejected':'pending'); ?><div style="display:flex;justify-content:space-between;gap:10px;padding:10px 0;border-bottom:1px solid #edf0f4"><div><?php echo htmlspecialchars($l['fullname']); ?> <span class="muted">· <?php echo htmlspecialchars($l['leave_type']); ?></span></div><span class="leave-status <?php echo $cls; ?>"><?php echo htmlspecialchars($l['status']); ?></span></div><?php }} ?></div>
<div class="panel"><h2>Recent activity</h2><?php if(!$activity){ ?><div class="empty-state"><strong>No recent activity</strong></div><?php } else { foreach($activity as $a){ ?><div style="padding:10px 0;border-bottom:1px solid #edf0f4"><div style="font-size:13px;color:#26384c"><?php echo htmlspecialchars($a['action']); ?></div><div class="muted small"><?php echo htmlspecialchars($a['fullname']); ?> · <?php echo date('d M Y, H:i',strtotime($a['created_at'])); ?></div></div><?php }} ?></div>
<div class="panel"><div style="display:flex;gap:12px;align-items:center"><div class="dept-icon" style="width:40px;height:40px"><i class="fa-solid fa-building"></i></div><div><strong style="color:#17263a"><?php echo $totalDepartments; ?> departments</strong><br><a href="../Departments/index.php" class="muted" style="text-decoration:none">Manage structure</a></div></div></div>
</div></section>
</main></div>
<script>
const deptLabels=<?php echo json_encode($deptLabels); ?>, deptValues=<?php echo json_encode($deptValues); ?>;
new Chart(document.getElementById('headcountChart'),{type:'bar',data:{labels:deptLabels,datasets:[{data:deptValues,borderRadius:7,backgroundColor:'#177dab',maxBarThickness:110}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{x:{grid:{display:false},ticks:{color:'#718198'}},y:{beginAtZero:true,ticks:{precision:0,color:'#718198'},grid:{color:'#e6ebf1'}}}}});
new Chart(document.getElementById('projectChart'),{type:'doughnut',data:{labels:['Planning','In Progress','Completed'],datasets:[{data:[<?php echo $planningProjects; ?>,<?php echo $inProgressProjects; ?>,<?php echo $completedProjects; ?>],backgroundColor:['#177dab','#2ba4a5','#249b65'],borderWidth:2,borderColor:'#fff'}]},options:{responsive:true,maintainAspectRatio:false,cutout:'58%',plugins:{legend:{display:false}}}});
</script>
</body></html>
