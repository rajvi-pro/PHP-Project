<?php
session_start();
include("conn.php");

// Admin login check
if (!isset($_SESSION['adminid'])) {
    header("Location: index.php");
    exit();
}

// Handle block/unblock via AJAX
if(isset($_GET['action'], $_GET['type'], $_GET['id'])){
    $id = intval($_GET['id']);
    $type = $_GET['type'];

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
    exit();
}

// Handle delete via AJAX
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

// Filter
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
body{font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin:0; background:#f4f6f9; color:#333;}

/* Sidebar */
.sidebar{position:fixed; left:0; top:0; width:220px; height:100%; background:#8bd5f7; color:white; padding-top:25px;}
.sidebar h2{text-align:center; margin-bottom:25px; font-size:20px; font-weight:600;}
.sidebar a{display:block; color:white; text-decoration:none; padding:14px 20px; margin-bottom:5px; border-radius:6px; transition:0.3s; font-weight:500;}
.sidebar a:hover{background:#5bb8e3;}

/* Topnav */
.topnav{margin-left:220px; background:white; padding:15px 20px; box-shadow:0 3px 10px rgba(0,0,0,0.1); display:flex; justify-content:space-between; align-items:center; font-weight:500; border-bottom:2px solid #8bd5f7;}
.topnav .welcome-card{background:#8bd5f7; padding:10px 15px; border-radius:8px; color:white; font-weight:600; display:flex; align-items:center; gap:10px; box-shadow:0 3px 10px rgba(0,0,0,0.1);}
.topnav .welcome-card i{font-size:18px;}
.topnav a{color:#8bd5f7; text-decoration:none; font-weight:600;}

/* Container */
.container{margin-left:220px; padding:30px;}

/* Filter */
select{padding:10px 12px; margin-bottom:20px; border-radius:6px; border:1px solid #ccc; font-size:16px; background:white; cursor:pointer; transition:0.3s;}
select:hover{box-shadow:0 2px 10px rgba(0,0,0,0.15);}

/* Card */
.card{background:white; padding:25px; border-radius:12px; box-shadow:0 6px 20px rgba(0,0,0,0.08); margin-bottom:30px;}

/* Tables */
table{width:100%; border-collapse:collapse; border-radius:12px; overflow:hidden; box-shadow:0 5px 15px rgba(0,0,0,0.05);}
th, td{padding:14px 18px; text-align:left;}
th{background:#8bd5f7; color:white; font-weight:600; font-size:15px;}
tr:nth-child(even){background:#f0f8ff;}
tr:hover{background:#d4edf9; transform:scale(1.01); transition:0.2s;}

/* Buttons */
.btn{padding:7px 14px; border:none; border-radius:6px; cursor:pointer; color:white; font-weight:500; transition:0.3s; margin-right:5px; font-size:14px;}
.btn-block{background:#ff6b6b;}
.btn-block:hover{background:#e04e4e; transform:scale(1.05);}
.btn-unblock{background:#28a745;}
.btn-unblock:hover{background:#218838; transform:scale(1.05);}
.btn-delete{background:#6c757d;}
.btn-delete:hover{background:#5a6268; transform:scale(1.05);}

/* Headers inside cards */
h2{margin-bottom:20px; color:#1c1c1c; font-size:22px; font-weight:600;}

/* Responsive */
@media(max-width:768px){
.sidebar{width:100%; height:auto; position:relative;}
.topnav, .container{margin-left:0;}
.topnav{flex-direction:column; gap:10px;}
}
</style>

<script>
function changeFilter(){ var val=document.getElementById('userFilter').value; window.location='?filter='+val; }
function deleteUser(id,type){ if(confirm('Are you sure you want to delete this user?')){ var xhr=new XMLHttpRequest(); xhr.open("POST","manage_users.php",true); xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded"); xhr.onload=function(){ if(this.responseText.trim()=='success'){ document.getElementById(type+'_'+id).remove(); } }; xhr.send("delete_user=1&id="+id+"&type="+type); } }
function actionUser(id,type,action){ var xhr=new XMLHttpRequest(); xhr.open("GET","manage_users.php?action="+action+"&type="+type+"&id="+id,true); xhr.send(); var btn=document.getElementById(action+'_'+type+'_'+id); var td=btn.parentElement; if(action=='block'){ td.innerHTML='<button class="btn btn-unblock" onclick="actionUser('+id+',\''+type+'\',\'unblock\')">Unblock</button> <button class="btn btn-delete" onclick="deleteUser('+id+',\''+type+'\')">Delete</button>'; } else { td.innerHTML='<button class="btn btn-block" onclick="actionUser('+id+',\''+type+'\',\'block\')">Block</button> <button class="btn btn-delete" onclick="deleteUser('+id+',\''+type+'\')">Delete</button>'; } }
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
<div class="welcome-card"><i class="fas fa-user-shield"></i> Welcome, Admin</div>
<div><a href="logout.php">Logout</a></div>
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
<thead><tr><th>#</th><th>Name</th><th>Email</th><th>Status</th><th>Actions</th></tr></thead>
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
<thead><tr><th>#</th><th>Company</th><th>Email</th><th>Status</th><th>Actions</th></tr></thead>
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
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
