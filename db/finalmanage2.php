<?php
session_start();
include("conn.php");

// Ensure admin login
if (!isset($_SESSION['adminid'])) {
    header("Location: index.php");
    exit();
}

// Handle block/unblock actions via GET
if(isset($_GET['action'], $_GET['type'], $_GET['id'])){
    $id = intval($_GET['id']);
    $type = $_GET['type']; // student or employer

    if($type=='student'){
        $table = 'student_info';
        $id_col = 'sid';
    } else {
        $table = 'employer_info';
        $id_col = 'eid';
    }

    if($_GET['action']=='block'){
        mysqli_query($conn,"UPDATE $table SET status='blocked' WHERE $id_col=$id");
    } elseif($_GET['action']=='unblock'){
        mysqli_query($conn,"UPDATE $table SET status='active' WHERE $id_col=$id");
    }
    exit(); // Stop execution for AJAX
}

// Handle delete via POST AJAX
if(isset($_POST['delete_user'])){
    $id = intval($_POST['id']);
    $type = $_POST['type'];

    if($type=='student'){
        mysqli_query($conn,"DELETE FROM student_info WHERE sid=$id");
    } else {
        mysqli_query($conn,"DELETE FROM employer_info WHERE eid=$id");
    }
    echo "success";
    exit();
}

// Determine filter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

$students = [];
$employers = [];

if($filter=='all' || $filter=='students'){
    $students = mysqli_query($conn,"SELECT * FROM student_info ORDER BY sid DESC");
}
if($filter=='all' || $filter=='employers'){
    $employers = mysqli_query($conn,"SELECT * FROM employer_info ORDER BY eid DESC");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin | Manage Users</title>
    <link rel="icon" href="images/favicon.ico">
    <style>
        /* General */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin:0; background:#f4f6f9; }
        h1 { text-align:center; margin-bottom:25px; color:#333; font-size:28px; }

        /* Sidebar */
        .sidebar { position: fixed; left:0; top:0; width:220px; height:100%; background:#007BFF; color:white; padding-top:20px; }
        .sidebar h2 { text-align:center; margin-bottom:20px; font-size:20px; }
        .sidebar a { display:block; color:white; text-decoration:none; padding:14px 20px; margin-bottom:5px; border-radius:4px; transition:0.3s; }
        .sidebar a:hover { background:#0056b3; }

        /* Topnav */
        .topnav { margin-left:220px; background:white; padding:15px 20px; box-shadow:0 2px 8px rgba(0,0,0,0.1); display:flex; justify-content:space-between; align-items:center; }

        /* Container */
        .container { margin-left:220px; padding:30px; }

        /* Filter */
        select { padding:10px 12px; margin-bottom:20px; border-radius:5px; border:1px solid #ccc; font-size:16px; }

        /* Card style */
        .card { background:white; padding:20px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.1); margin-bottom:30px; }

        /* Tables */
        table { width:100%; border-collapse:collapse; }
        th, td { padding:12px 15px; text-align:left; }
        th { background:#007BFF; color:white; font-weight:500; font-size:15px; }
        tr { transition:0.3s; }
        tr:hover { background:#f1f1f1; transform:scale(1.01); }

        /* Buttons */
        .btn { padding:6px 12px; border:none; border-radius:5px; cursor:pointer; color:white; font-weight:500; transition:0.3s; margin-right:5px; font-size:14px; }
        .btn-block { background:#dc3545; }
        .btn-block:hover { background:#c82333; transform:scale(1.05); }
        .btn-unblock { background:#28a745; }
        .btn-unblock:hover { background:#218838; transform:scale(1.05); }
        .btn-delete { background:#6c757d; }
        .btn-delete:hover { background:#5a6268; transform:scale(1.05); }

        h2 { margin-bottom:15px; color:#333; font-size:22px; }

        /* Responsive */
        @media(max-width:768px){
            .sidebar { width:100%; height:auto; position:relative; }
            .topnav, .container { margin-left:0; }
        }
    </style>

    <script>
        function changeFilter(){
            var val = document.getElementById('userFilter').value;
            window.location = '?filter=' + val;
        }

        function deleteUser(id, type){
            if(confirm('Are you sure you want to delete this user?')){
                var xhr = new XMLHttpRequest();
                xhr.open("POST","manage_users.php",true);
                xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                xhr.onload = function(){
                    if(this.responseText.trim()=='success'){
                        document.getElementById(type+'_'+id).remove();
                    }
                };
                xhr.send("delete_user=1&id="+id+"&type="+type);
            }
        }

        function actionUser(id,type,action){
            var xhr = new XMLHttpRequest();
            xhr.open("GET","manage_users.php?action="+action+"&type="+type+"&id="+id,true);
            xhr.send();
            var btn = document.getElementById(action+'_'+type+'_'+id);
            var td = btn.parentElement;
            if(action=='block'){
                td.innerHTML = '<button class="btn btn-unblock" onclick="actionUser('+id+',\''+type+'\',\'unblock\')">Unblock</button> <button class="btn btn-delete" onclick="deleteUser('+id+',\''+type+'\')">Delete</button>';
            } else {
                td.innerHTML = '<button class="btn btn-block" onclick="actionUser('+id+',\''+type+'\',\'block\')">Block</button> <button class="btn btn-delete" onclick="deleteUser('+id+',\''+type+'\')">Delete</button>';
            }
        }
    </script>
</head>
<body>
<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_internships.php">Manage Internships</a>
    <a href="reports.php">Reports</a>
    <a href="change_password.php">Change Password</a>
    <a href="logout.php">Logout</a>
</div>

<div class="topnav">
    <div>Welcome, Admin</div>
    <div><a href="logout.php" style="color:#007BFF;text-decoration:none;">Logout</a></div>
</div>

<div class="container">
<h1>Manage Users</h1>

<select id="userFilter" onchange="changeFilter()">
    <option value="all" <?php if($filter=='all') echo 'selected'; ?>>All</option>
    <option value="students" <?php if($filter=='students') echo 'selected'; ?>>Students</option>
    <option value="employers" <?php if($filter=='employers') echo 'selected'; ?>>Employers</option>
</select>

<?php if($filter=='all' || $filter=='students'){ ?>
<div class="card">
<h2>Students</h2>
<table>
<thead>
<tr><th>#</th><th>Name</th><th>Email</th><th>Status</th><th>Actions</th></tr>
</thead>
<tbody>
<?php $i=1; while($s=mysqli_fetch_assoc($students)){ ?>
<tr id="student_<?php echo $s['sid']; ?>">
<td><?php echo $i++; ?></td>
<td><?php echo htmlspecialchars($s['stu_fname'].' '.$s['stu_lname']); ?></td>
<td><?php echo htmlspecialchars($s['stu_email']); ?></td>
<td><?php echo ucfirst($s['status']); ?></td>
<td>
<?php if($s['status']=='active'){ ?>
<button class="btn btn-block" id="block_student_<?php echo $s['sid']; ?>" onclick="actionUser(<?php echo $s['sid']; ?>,'student','block')">Block</button>
<?php } else { ?>
<button class="btn btn-unblock" id="unblock_student_<?php echo $s['sid']; ?>" onclick="actionUser(<?php echo $s['sid']; ?>,'student','unblock')">Unblock</button>
<?php } ?>
<button class="btn btn-delete" onclick="deleteUser(<?php echo $s['sid']; ?>,'student')">Delete</button>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<?php } ?>

<?php if($filter=='all' || $filter=='employers'){ ?>
<div class="card">
<h2>Employers</h2>
<table>
<thead>
<tr><th>#</th><th>Company</th><th>Email</th><th>Status</th><th>Actions</th></tr>
</thead>
<tbody>
<?php $i=1; while($e=mysqli_fetch_assoc($employers)){ ?>
<tr id="employer_<?php echo $e['eid']; ?>">
<td><?php echo $i++; ?></td>
<td><?php echo htmlspecialchars($e['com_name']); ?></td>
<td><?php echo htmlspecialchars($e['emp_email']); ?></td>
<td><?php echo ucfirst($e['status']); ?></td>
<td>
<?php if($e['status']=='active'){ ?>
<button class="btn btn-block" id="block_employer_<?php echo $e['eid']; ?>" onclick="actionUser(<?php echo $e['eid']; ?>,'employer','block')">Block</button>
<?php } else { ?>
<button class="btn btn-unblock" id="unblock_employer_<?php echo $e['eid']; ?>" onclick="actionUser(<?php echo $e['eid']; ?>,'employer','unblock')">Unblock</button>
<?php } ?>
<button class="btn btn-delete" onclick="deleteUser(<?php echo $e['eid']; ?>,'employer')">Delete</button>
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
