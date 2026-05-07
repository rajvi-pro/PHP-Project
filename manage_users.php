<?php
session_start();
include("conn.php");

// ✅ Admin login check
if (!isset($_SESSION['adminid'])) {
    header("Location: index.php");
    exit();
}

// ✅ Helper function to map user type
function get_table_info($type)
{
    if ($type === 'student') {
        return ['table' => 'student_info', 'id_col' => 'sid', 'name_col' => 'stu_fname', 'lname_col' => 'stu_lname', 'email_col' => 'stu_email'];
    } elseif ($type === 'employer') {
        return ['table' => 'employer_info', 'id_col' => 'eid', 'company_col' => 'com_name', 'email_col' => 'emp_email'];
    }
    return false;
}

// ✅ Block / Unblock via AJAX
if (isset($_GET['action'], $_GET['type'], $_GET['id'])) {
    header('Content-Type: application/json; charset=utf-8');

    $id = intval($_GET['id']);
    $type = $_GET['type'];
    $info = get_table_info($type);

    if (!$info) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid type']);
        exit();
    }

    $table = $info['table'];
    $id_col = $info['id_col'];
    $action = $_GET['action'];

    $new_status = ($action === "block") ? "blocked" : "active";
    $q = "UPDATE $table SET status='$new_status' WHERE $id_col = $id AND is_deleted = 0";

    if (mysqli_query($conn, $q)) {
        echo json_encode(['status' => 'success', 'new_status' => $new_status]);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
    exit();
}

// ✅ Soft Delete User (is_deleted = 1)
if (isset($_POST['delete_user'])) {
    header('Content-Type: application/json; charset=utf-8');

    $id = intval($_POST['id']);
    $type = $_POST['type'];
    $info = get_table_info($type);

    if (!$info) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid type']);
        exit();
    }

    $q = "UPDATE {$info['table']} SET is_deleted = 1 WHERE {$info['id_col']} = $id";
    mysqli_query($conn, $q);

    echo json_encode(['status' => 'success']);
    exit();
}

// ✅ Filter
$filter = $_GET['filter'] ?? 'all';
if ($filter == 'all' || $filter == 'students') {
    $students = mysqli_query($conn, "SELECT * FROM student_info WHERE is_deleted = 0 ORDER BY sid DESC");
}
if ($filter == 'all' || $filter == 'employers') {
    $employers = mysqli_query($conn, "SELECT * FROM employer_info WHERE is_deleted = 0 ORDER BY eid DESC");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin | Manage Users</title>
<link rel="icon" href="images/favicon.ico">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
/* ========= GLOBAL ========= */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    background: #f4f6f9;
}

/* ========= SIDEBAR ========= */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 230px;
    height: 100vh;
    background: #003499;
    color: white;
    display: flex;
    flex-direction: column;
}

.sidebar-header {
    text-align: center;
    padding: 25px 10px;
}

.sidebar-header h2 {
    margin: 10px 0;
    font-size: 19px;
    font-weight: bold;
}

.sidebar-menu {
    list-style: none;
    padding: 0;
    margin-top: 15px;
}

.sidebar-menu li {
    width: 100%;
}

.sidebar-menu a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px 22px;
    font-size: 16px;
    color: #ffffff;
    text-decoration: none;
    transition: 0.25s;
}

.sidebar-menu a:hover,
.sidebar-menu a.active {
    background: #005ce6;
    padding-left: 30px;
}

/* ========= TOP NAV ========= */
.topnav {
    margin-left: 230px;
    background: white;
    padding: 15px 20px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 3px solid #8bd5f7;
}

.container {
    margin-left: 230px;
    padding: 30px;
}

/* ========= TABLE + BUTTONS ========= */
.card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    margin-bottom: 30px;
}

table {
    width: 100%;
    border-collapse: collapse;
    overflow: hidden;
    border-radius: 10px;
}

table th {
    background: #005ce6;
    color: white;
    padding: 12px;
}

table td {
    padding: 12px;
}

tr:nth-child(even) { background: #eef5ff; }

.btn {
    padding: 8px 14px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    color: white;
}

.btn-block { background: #ff4d4d; }
.btn-block:hover { background: #d63333; }

.btn-unblock { background: #28a745; }
.btn-unblock:hover { background: #208838; }

.btn-delete { background: #6c757d; }
.btn-delete:hover { background: #575f67; }
</style>

<script>
function changeFilter(){
    window.location = "?filter=" + document.getElementById("userFilter").value;
}

// ✅ Delete User
async function deleteUser(id, type){
    if(!confirm("Delete this user?")) return;
    let data = new URLSearchParams({ delete_user: "1", id, type });
    let res = await fetch("manage_users.php", { method: "POST", body: data });
    document.getElementById(type + "_" + id).remove();
}

// ✅ Block / Unblock User
async function actionUser(id, type, action){
    let res = await fetch(`manage_users.php?action=${action}&type=${type}&id=${id}`);
    let data = await res.json();

    if(data.status === "success"){
        location.reload();
    }
}
</script>
</head>

<body>

<!-- ✅ SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-header">
        <h2>Admin Panel</h2>
    </div>

    <ul class="sidebar-menu">
        <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="manage_users.php" class="active"><i class="fas fa-users"></i> Manage Users</a></li>
        <li><a href="categories.php"><i class="fas fa-briefcase"></i>Categories</a></li>
        <li><a href="reports.php"><i class="fas fa-file-alt"></i> Reports</a></li>
        <li><a href="admin_change_password.php"><i class="fas fa-key"></i> Change Password</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

<!-- ✅ TOP NAV -->
<div class="topnav">
    <div><b>Welcome, Admin</b></div>
    <a href="logout.php" style="color:#003499; font-weight:bold;">Logout</a>
</div>

<!-- ✅ PAGE CONTENT -->
<div class="container">

<h1>Manage Users</h1>

<select id="userFilter" onchange="changeFilter()">
    <option value="all" <?= ($filter=='all')?'selected':'' ?>>All</option>
    <option value="students" <?= ($filter=='students')?'selected':'' ?>>Students</option>
    <option value="employers" <?= ($filter=='employers')?'selected':'' ?>>Employers</option>
</select>

<?php if ($filter=='all' || $filter=='students') { ?>
<div class="card">
<h2>Students</h2>
<table>
<thead><tr><th>#</th><th>Name</th><th>Email</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>
<?php $i=1; while($s=mysqli_fetch_assoc($students)){ ?>
<tr id="student_<?= $s['sid']; ?>">
<td><?= $i++; ?></td>
<td><?= $s['stu_fname'].' '.$s['stu_lname']; ?></td>
<td><?= $s['stu_email']; ?></td>
<td><?= ucfirst($s['status']); ?></td>
<td>
<?php if($s['status']=="active"){ ?>
<button class="btn btn-block" onclick="actionUser(<?= $s['sid']; ?>,'student','block')">Block</button>
<?php } else { ?>
<button class="btn btn-unblock" onclick="actionUser(<?= $s['sid']; ?>,'student','unblock')">Unblock</button>
<?php } ?>
<button class="btn btn-delete" onclick="deleteUser(<?= $s['sid']; ?>,'student')">Delete</button>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<?php } ?>

<?php if ($filter=='all' || $filter=='employers') { ?>
<div class="card">
<h2>Employers</h2>
<table>
<thead><tr><th>#</th><th>Company</th><th>Email</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>
<?php $i=1; while($e=mysqli_fetch_assoc($employers)){ ?>
<tr id="employer_<?= $e['eid']; ?>">
<td><?= $i++; ?></td>
<td><?= $e['com_name']; ?></td>
<td><?= $e['emp_email']; ?></td>
<td><?= ucfirst($e['status']); ?></td>
<td>
<?php if($e['status']=="active"){ ?>
<button class="btn btn-block" onclick="actionUser(<?= $e['eid']; ?>,'employer','block')">Block</button>
<?php } else { ?>
<button class="btn btn-unblock" onclick="actionUser(<?= $e['eid']; ?>,'employer','unblock')">Unblock</button>
<?php } ?>
<button class="btn btn-delete" onclick="deleteUser(<?= $e['eid']; ?>,'employer')">Delete</button>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<?php } ?>
</div>

</body>
</html>
