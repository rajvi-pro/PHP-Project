<?php
session_start();
include("conn.php");

// Redirect if not admin
if (!isset($_SESSION['adminId'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch data
$students = mysqli_query($conn, "SELECT * FROM students ORDER BY sid DESC");
$employees = mysqli_query($conn, "SELECT * FROM employers ORDER BY eid DESC");
$internships = mysqli_query($conn, "SELECT * FROM internship_details ORDER BY ip_id DESC");
$applications = mysqli_query($conn, "SELECT * FROM applications ORDER BY appid DESC");
?>
<html>
<head>
    <title>Internshop | Admin Dashboard</title>
    <link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/admin_dashboard_style.css">
    <style>
        /* Top nav for admin */
        .admin-nav {
            background-color: #f3703a;
            overflow: hidden;
            padding: 10px 20px;
        }
        .admin-nav a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
            font-weight: bold;
        }
        .admin-nav a:hover {
            text-decoration: underline;
        }
        .admin-section { margin-top: 20px; }
        .dashboard-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .dashboard-table th, .dashboard-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .dashboard-table th { background-color: #f3703a; color: white; }
        .dashboard-table tr:nth-child(even){background-color: #f9f9f9;}
        .dashboard-table tr:hover {background-color: #f1f1f1;}
    </style>
</head>
<body>
<header>
    <?php include("nav.php"); ?>
</header>

<main class="admin-dash-container">
    <h2>Admin Dashboard</h2>
    
    <!-- Admin Top Navigation -->
    <div class="admin-nav">
        <a href="admin_dashboard.php?section=dashboard">Dashboard</a>
        <a href="admin_dashboard.php?section=students">Manage Students</a>
        <a href="admin_dashboard.php?section=employees">Manage Employees</a>
        <a href="admin_dashboard.php?section=block">Block/Unblock Users</a>
        <a href="admin_dashboard.php?section=settings">Settings</a>
        <a href="admin_logout.php">Logout</a>
    </div>

    <div class="admin-section">
    <?php
    $section = isset($_GET['section']) ? $_GET['section'] : 'dashboard';

    // Dashboard Overview
    if($section == 'dashboard') {
        echo "<h3>Overview</h3>
              <p>Students: ".mysqli_num_rows($students)."</p>
              <p>Employees: ".mysqli_num_rows($employees)."</p>
              <p>Internships: ".mysqli_num_rows($internships)."</p>
              <p>Applications: ".mysqli_num_rows($applications)."</p>";
    }

    // Students Section
    else if($section == 'students') {
        echo "<h3>Registered Students</h3>";
        if(mysqli_num_rows($students) > 0) {
            echo "<table class='dashboard-table'>
                    <tr><th>Name</th><th>Email</th><th>Action</th></tr>";
            while($stu = mysqli_fetch_assoc($students)) {
                echo "<tr>
                        <td>".htmlspecialchars($stu['name'])."</td>
                        <td>".htmlspecialchars($stu['email'])."</td>
                        <td>View / Delete</td>
                      </tr>";
            }
            echo "</table>";
        } else { echo "<p>No students registered.</p>"; }
    }

    // Employees Section
    else if($section == 'employees') {
        echo "<h3>Registered Employees</h3>";
        if(mysqli_num_rows($employees) > 0) {
            echo "<table class='dashboard-table'>
                    <tr><th>Name</th><th>Email</th><th>Status</th><th>Action</th></tr>";
            while($emp = mysqli_fetch_assoc($employees)) {
                echo "<tr>
                        <td>".htmlspecialchars($emp['name'])."</td>
                        <td>".htmlspecialchars($emp['email'])."</td>
                        <td>{$emp['status']}</td>
                        <td>
                            <a href='block_user.php?eid={$emp['eid']}'>Block/Unblock</a> | 
                            <a href='delete_employee.php?eid={$emp['eid']}' onclick=\"return confirm('Delete this employee?');\">Delete</a>
                        </td>
                      </tr>";
            }
            echo "</table>";
        } else { echo "<p>No employees registered.</p>"; }
    }

    // Block/Unblock Section
    else if($section == 'block') {
        echo "<h3>Blocked Users</h3>";
        echo "<p>Manage user status here.</p>";
    }

    // Settings Section
    else if($section == 'settings') {
        echo "<h3>Admin Settings</h3>
              <a href='admin_settings.php'>Change Password / Email</a>";
    }
    ?>
    </div>
</main>

<footer>
    <?php include("footer.php"); ?>
</footer>
</body>
</html>
