<?php
$currentPath = $_SERVER["PHP_SELF"] ?? "";

$role = $_SESSION["role"] ?? "Employee";

?>
<aside class="sidebar">

    <div class="brand">

        <div class="brand-mark">O</div>

        <div>

            <div class="brand-name">OpsFlow</div>

            <div class="brand-subtitle">Operations Suite</div>

        </div>

    </div>


    <div class="nav-section">

        <div class="nav-section-title">WORKSPACE</div>

        <a class="nav-item 
<?php echo strpos($currentPath, '/Dashboard/') !== false ? 'active' : '';
 
?>" href="../Dashboard/index.php">
<i class="fa-solid fa-border-all">
</i>
<span>Dashboard</span>
</a>

        <a class="nav-item 
<?php echo strpos($currentPath, '/Employees/') !== false ? 'active' : '';
 
?>" href="../Employees/index.php">
<i class="fa-solid fa-users">
</i>
<span>Employees</span>
</a>

        <a class="nav-item 
<?php echo strpos($currentPath, '/Departments/') !== false ? 'active' : '';
 
?>" href="../Departments/index.php">
<i class="fa-solid fa-building">
</i>
<span>Departments</span>
</a>

        <a class="nav-item 
<?php echo strpos($currentPath, '/Projects/') !== false ? 'active' : '';
 
?>" href="../Projects/index.php">
<i class="fa-regular fa-folder">
</i>
<span>Projects</span>
</a>

        <a class="nav-item 
<?php echo strpos($currentPath, '/Attendance/') !== false ? 'active' : '';
 
?>" href="../Attendance/index.php">
<i class="fa-regular fa-calendar-check">
</i>
<span>Attendance</span>
</a>

        <a class="nav-item 
<?php echo strpos($currentPath, '/Leave/') !== false ? 'active' : '';
 
?>" href="../Leave/index.php">
<i class="fa-regular fa-paper-plane">
</i>
<span>Leave</span>
</a>

        <a class="nav-item 
<?php echo strpos($currentPath, '/Reports/') !== false ? 'active' : '';
 
?>" href="../Reports/index.php">
<i class="fa-solid fa-chart-column">
</i>
<span>Reports</span>
</a>

    </div>


    
<?php if ($role === "Admin") {
 
?>
    <div class="nav-section">

        <div class="nav-section-title">ADMINISTRATION</div>

        <a class="nav-item 
<?php echo strpos($currentPath, '/Users/') !== false ? 'active' : '';
 
?>" href="../Users/index.php">
<i class="fa-regular fa-circle-user">
</i>
<span>Users &amp; Roles</span>
</a>

        <a class="nav-item 
<?php echo strpos($currentPath, '/Settings/') !== false ? 'active' : '';
 
?>" href="../Settings/index.php">
<i class="fa-solid fa-gear">
</i>
<span>Settings</span>
</a>

    </div>

    
<?php }
 
?>

    <div class="sidebar-user">

        <div class="avatar">
<?php echo strtoupper(substr($_SESSION["fullname"] ?? "U", 0, 2));
 
?></div>

        <div class="sidebar-user-text">

            <strong>
<?php echo htmlspecialchars($_SESSION["fullname"] ?? "User");
 
?></strong>

            <span>
<?php echo htmlspecialchars($role);
 
?></span>

        </div>

    </div>

</aside>
