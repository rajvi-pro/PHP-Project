<?php
session_start(); // to own box to store informations
include("conn.php"); // execute the code from another file

// Ensure admin login
if (!isset($_SESSION['adminid'])) { // isset-> built-in-function 
    header("Location: index.php");
    exit();
}

/* ======================= DASHBOARD COUNTS ======================= */
$total_students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM student_info"))['total'];
$total_companies = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM employer_info"))['total'];

/* ======================= MANAGE INTERNSHIP ACTIONS ======================= */
if (isset($_GET['action'], $_GET['id'])) { //intval-> takes any value
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action == "delete") {
        mysqli_query($conn, "UPDATE internship_details SET is_deleted=1 WHERE ip_id=$id");
    } elseif ($action == "toggle") {
        $current = mysqli_fetch_assoc(mysqli_query($conn, "SELECT status FROM internship_details WHERE ip_id=$id"))['status'];
        $new = ($current == 'active') ? 'inactive' : 'active';
        mysqli_query($conn, "UPDATE internship_details SET status='$new' WHERE ip_id=$id AND is_deleted=0");
    }
    exit();
}

/* ======================= FETCH INTERNSHIP LIST WITH APPLICATION COUNT ======================= */
$internships = mysqli_query($conn, "
    SELECT i.ip_id, i.title, e.com_name, i.status,
           COUNT(a.appid) AS total_applied
    FROM internship_details i
    JOIN employer_info e ON i.eid = e.eid
    LEFT JOIN applications a ON i.ip_id = a.ip_id AND a.is_deleted=0
    WHERE i.is_deleted=0
    GROUP BY i.ip_id
    ORDER BY i.ip_id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin | Dashboard + Manage Internships</title>
    <link rel="icon" href="images/favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body { font-family: Arial, sans-serif; margin:0; background:#f4f6f9; }

        /* Sidebar */
        .sidebar { position:fixed; top:0; left:0; width:230px; height:100vh; background:#003499; color:white; display:flex; flex-direction:column; }
        .sidebar-header { text-align:center; padding:25px 10px; }
        .sidebar-header h2 { margin:10px 0; font-size:19px; font-weight:bold; }
        .sidebar-menu { list-style:none; padding:0; margin-top:15px; }
        .sidebar-menu a { display:flex; align-items:center; gap:12px; padding:15px 22px; color:white; text-decoration:none; font-size:16px; transition:.25s; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background:#005ce6; padding-left:30px; }

        /* Top nav */
        .topnav { margin-left:230px; background:white; padding:15px 20px; box-shadow:0 2px 8px rgba(0,0,0,0.1); display:flex; justify-content:space-between; }
        .topnav a { color:#003499; font-weight:bold; text-decoration:none; }

        /* Main */
        .container { margin-left:230px; padding:30px; }
        h1 { text-align:center; color:#333; margin-bottom:25px; }

        /* Stat boxes */
        .stats { display:flex; gap:20px; justify-content:space-around; margin-bottom:30px; }
        .stat-box { background:white; flex:1; text-align:center; padding:20px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.1); }
        .stat-box h3 { font-size:18px; color:#555; margin:0; }
        .stat-box p { font-size:24px; font-weight:bold; color:#003499; margin:5px 0 0 0; }

        /* Internship Table */
        table { width:100%; border-collapse:collapse; }
        table th { background:#005ce6; color:white; padding:12px; }
        table td { padding:12px; }
        tr:nth-child(even) { background:#eef5ff; }

        .card { background:white; padding:25px; border-radius:12px; box-shadow:0 6px 20px rgba(0,0,0,0.08); }

        .status-badge { padding:5px 10px; border-radius:8px; font-weight:600; color:white; }
        .active { background:#28a745; }
        .inactive { background:#dc3545; }

        .btn { padding:7px 14px; border:none; border-radius:6px; cursor:pointer; font-size:14px; margin:2px; color:white; }
        .btn-toggle { background:#17a2b8; }
        .btn-toggle:hover { background:#138496; }
        .btn-delete { background:#6c757d; }
        .btn-delete:hover { background:#575f67; }
    </style>

    <script>
        function toggleStatus(id){ if(confirm("Do you want to toggle status?")){ fetch(`admin_dashboard.php?action=toggle&id=${id}`).then(()=>location.reload()); }}
        function deleteInternship(id){ if(confirm("Delete this internship? (Soft Delete)")){ fetch(`admin_dashboard.php?action=delete&id=${id}`).then(()=>location.reload()); }}
        function showApplicants(id){ window.location = "view_applicants.php?ip_id=" + id; }
    </script>
</head>

<body>
<div class="sidebar">
    <div class="sidebar-header"><h2>Admin Panel</h2></div>
    <ul class="sidebar-menu">
        <li><a href="admin_dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a></li>
        <li><a href="categories.php"><i class="fas fa-briefcase"></i>Categories</a></li>
        <li><a href="reports.php"><i class="fas fa-chart-line"></i> Reports</a></li>
        <li><a href="admin_change_password.php"><i class="fas fa-key"></i> Change Password</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

<div class="topnav"><span>Welcome, Admin</span><a href="logout.php">Logout</a></div>

<div class="container">
    <h1>Admin Dashboard</h1>

    <div class="stats">
        <div class="stat-box"><h3>Total Students</h3><p><?= $total_students ?></p></div>
        <div class="stat-box"><h3>Total Companies</h3><p><?= $total_companies ?></p></div>
    </div>

    <div class="card">
        <h2>Manage Internships & Applications</h2>
        <table>
            <thead>
                <tr><th>#</th><th>Internship</th><th>Company</th><th>Applicants</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php $i=1; while($row=mysqli_fetch_assoc($internships)) { ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= htmlspecialchars($row['title']); ?></td>
                        <td><?= htmlspecialchars($row['com_name']); ?></td>
                        <td><?= $row['total_applied']; ?></td>
                        <td><span class="status-badge <?= $row['status']; ?>"> <?= ucfirst($row['status']); ?> </span></td>
                        <td>
                            <button class="btn btn-toggle" onclick="toggleStatus(<?= $row['ip_id']; ?>)"><i class="fas fa-sync"></i> Toggle</button>
                            <button class="btn btn-delete" onclick="deleteInternship(<?= $row['ip_id']; ?>)"><i class="fas fa-trash"></i> Delete</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
