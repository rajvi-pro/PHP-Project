<?php
session_start();
include("conn.php");

// Check if admin is logged in
if (!isset($_SESSION['adminid'])) {
    header("Location: index.php");
    exit();
}

// Handle employer block/unblock
if (isset($_GET['toggle_user']) && !empty($_GET['toggle_user']) && isset($_GET['type'])) {
    $user_id = mysqli_real_escape_string($conn, $_GET['toggle_user']);
    $type = $_GET['type'];

    if ($type === 'employee') {  // Only employees have status
        $query = "SELECT status FROM employer_info WHERE eid=$user_id";
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $new_status = ($row['status']==='blocked') ? 'active' : 'blocked';
            mysqli_query($conn, "UPDATE employer_info SET status='$new_status' WHERE eid=$user_id");
            echo "<script>showNotify('Employee status updated.',2); setTimeout(()=>location.replace('admin_dashboard.php'),2000);</script>";
        }
    }
}

// Handle internship delete
if (isset($_GET['rem_ip_id']) && !empty($_GET['rem_ip_id'])) {
    $ip_id = mysqli_real_escape_string($conn, $_GET['rem_ip_id']);
    mysqli_query($conn, "UPDATE internship_details SET is_deleted = 1 WHERE ip_id = $ip_id");
    echo "<script>showNotify('Internship deleted successfully.',2); setTimeout(()=>location.replace('admin_dashboard.php'),2000);</script>";
}

// Handle application delete
if (isset($_GET['rem_app_id']) && !empty($_GET['rem_app_id'])) {
    $appid = mysqli_real_escape_string($conn, $_GET['rem_app_id']);
    mysqli_query($conn, "DELETE FROM applications WHERE appid = $appid");
    echo "<script>showNotify('Application deleted successfully.',2); setTimeout(()=>location.replace('admin_dashboard.php'),2000);</script>";
}

// Fetch students (no status column)
$students = mysqli_query($conn, "SELECT sid, CONCAT(stu_fname,' ',stu_lname) AS student_name, stu_email AS email FROM student_info ORDER BY sid DESC");

// Fetch employees
$employees = mysqli_query($conn, "SELECT eid, CONCAT(emp_fname,' ',emp_lname) AS emp_name, emp_email AS email, status FROM employer_info ORDER BY eid DESC");

// Fetch internships
$internships = mysqli_query($conn, "SELECT ip_id, profile, eid, action FROM internship_details WHERE is_deleted=0 ORDER BY ip_id DESC");

// Fetch applications with student name & internship profile
$applications = mysqli_query($conn, "SELECT a.appid, a.sid, a.ip_id, a.status, CONCAT(s.stu_fname,' ',s.stu_lname) AS student_name, id.profile AS internship_profile 
                                     FROM applications a
                                     JOIN student_info s ON a.sid=s.sid
                                     JOIN internship_details id ON a.ip_id=id.ip_id
                                     ORDER BY a.appid DESC");
?>
<html>
<head>
    <title>Internshop | Admin Dashboard</title>
    <link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/admin_dashboard_style.css">
    <script type="text/javascript" src="script/errorMessage.js"></script>
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


    <!-- Students Report -->
    <div class="dashboard-section">
        <h3>Registered Students</h3>
        <table>
            <tr class="table-head"><th>Name</th><th>Email</th></tr>
            <?php while($s = mysqli_fetch_assoc($students)) {
                echo "<tr>
                        <td>{$s['student_name']}</td>
                        <td>{$s['email']}</td>
                      </tr>";
            } ?>
        </table>
        <p><i>Note: Students cannot be blocked as there is no status column.</i></p>
    </div>

    <!-- Employees Report -->
    <div class="dashboard-section">
        <h3>Registered Employees</h3>
        <table>
            <tr class="table-head"><th>Name</th><th>Email</th><th>Status</th><th>Action</th></tr>
            <?php while($e = mysqli_fetch_assoc($employees)) {
                echo "<tr>
                        <td>{$e['emp_name']}</td>
                        <td>{$e['email']}</td>
                        <td>{$e['status']}</td>
                        <td><a href='admin_dashboard.php?toggle_user={$e['eid']}&type=employee'>Block/Unblock</a></td>
                      </tr>";
            } ?>
        </table>
    </div>

    <!-- Internships Management -->
    <div class="dashboard-section">
        <h3>All Internships</h3>
        <table>
            <tr class="table-head"><th>Profile</th><th>Employer ID</th><th>Status</th><th>Action</th></tr>
            <?php while($ip = mysqli_fetch_assoc($internships)) {
                echo "<tr>
                        <td>{$ip['profile']}</td>
                        <td>{$ip['eid']}</td>
                        <td>{$ip['action']}</td>
                        <td><a href='admin_dashboard.php?rem_ip_id={$ip['ip_id']}' onclick='return confirmDelete();'>Delete</a></td>
                      </tr>";
            } ?>
        </table>
    </div>

    <!-- Applications Management -->
    <div class="dashboard-section">
        <h3>All Applications</h3>
        <table>
            <tr class="table-head"><th>Student</th><th>Internship</th><th>Status</th><th>Action</th></tr>
            <?php while($app = mysqli_fetch_assoc($applications)) {
                echo "<tr>
                        <td>{$app['student_name']}</td>
                        <td>{$app['internship_profile']}</td>
                        <td>{$app['status']}</td>
                        <td><a href='admin_dashboard.php?rem_app_id={$app['appid']}' onclick='return confirmDelete();'>Delete</a></td>
                      </tr>";
            } ?>
        </table>
    </div>

</div>
</main>

<footer>
    <?php include("footer.php"); ?>
</footer>

<script type="text/javascript">
    function confirmDelete() {
        return confirm("Do you really want to delete this?");
    }
</script>
</body>
</html>
