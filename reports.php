<?php
session_start();
include("conn.php");

// ✅ Check admin login
if (!isset($_SESSION['adminid'])) {
    header("Location: index.php");
    exit();
}

// ✅ Export Excel (CSV)
if (isset($_GET['export']) && $_GET['export'] == "excel") {
    $start = $_GET['start'];
    $end   = $_GET['end'];

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=internship_report.csv");

    $query = "
        SELECT internship_details.title, internship_details.start_date, internship_details.apply_by,
               student_info.stu_fname, student_info.stu_lname, student_info.stu_email, student_info.stu_phone,
               applications.applied_on
        FROM applications
        INNER JOIN internship_details ON applications.ip_id = internship_details.ip_id
        INNER JOIN student_info ON applications.sid = student_info.sid
        WHERE internship_details.start_date BETWEEN '$start' AND '$end'
        ORDER BY internship_details.start_date ASC
    ";
    $result = mysqli_query($conn, $query);

    echo "Internship Title,Start Date,Apply By,Student Name,Email,Phone,Applied On\n";
    while ($r = mysqli_fetch_assoc($result)) {
        echo "{$r['title']},{$r['start_date']},{$r['apply_by']}," .
             "{$r['stu_fname']} {$r['stu_lname']},{$r['stu_email']}," .
             "{$r['stu_phone']},{$r['applied_on']}\n";
    }
    exit();
}

// ✅ Handle Report Filter
$reportData = null;
if (isset($_POST['filter'])) {
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];

    $query = "
        SELECT internship_details.title, internship_details.start_date, internship_details.apply_by,
               student_info.stu_fname, student_info.stu_lname, student_info.stu_email, student_info.stu_phone,
               applications.applied_on
        FROM applications
        INNER JOIN internship_details ON applications.ip_id = internship_details.ip_id
        INNER JOIN student_info ON applications.sid = student_info.sid
        WHERE internship_details.start_date BETWEEN '$start' AND '$end'
        ORDER BY internship_details.start_date ASC
    ";

    $reportData = mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin | Reports</title>
<link rel="icon" href="images/favicon.ico">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
/* ========= GLOBAL ========= */
body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f9; }

/* ========= SIDEBAR ========= */
.sidebar {
    position: fixed; top: 0; left: 0;
    width: 230px; height: 100vh;
    background: #003499; color: white;
    display: flex; flex-direction: column;
}
.sidebar-header { text-align: center; padding: 25px 10px; }
.sidebar-header h2 { font-size: 19px; font-weight: bold; }
.sidebar-menu { list-style: none; padding: 0; margin-top: 15px; }
.sidebar-menu a {
    display: flex; align-items: center; gap: 12px;
    padding: 15px 22px; font-size: 16px; color: white;
    text-decoration: none; transition: 0.25s;
}
.sidebar-menu a:hover, .sidebar-menu a.active {
    background: #005ce6; padding-left: 30px;
}

/* ========= CONTENT ========= */
.container { margin-left: 230px; padding: 30px; }
.card {
    background: white; padding: 25px;
    border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}
.card-title {
    font-size: 22px; font-weight: bold;
    color: #005ce6;
}

/* ========= FORM ========= */
form { display: flex; gap: 30px; align-items: center; margin-top: 20px; }
input[type="date"] {
    padding: 10px; font-size: 15px;
    border-radius: 6px; border: 1px solid #bbb;
}
button {
    padding: 10px 18px; border: none;
    background: #005ce6; color: white;
    border-radius: 6px; cursor: pointer;
}
button:hover { background: #0042ad; }

/* ========= TABLE ========= */
table { width: 100%; border-collapse: collapse; margin-top: 25px; }
table th { background: #005ce6; color: white; padding: 12px; }
table td {
    padding: 12px; background: #f4f7ff;
    border-bottom: 1px solid #dbe3ff;
}
tr:nth-child(even) { background-color: #eaf1ff; }
tr:hover td { background-color: #d3e2ff; }

/* ========= EXPORT BUTTONS ========= */
.export-buttons { margin-top: 15px; display: flex; gap: 15px; }
.export-btn {
    padding: 8px 14px; border-radius: 6px;
    border: none; color: white; cursor: pointer;
}
.excel { background: #198754; }
.excel:hover { background: #0c6c41; }
.pdf { background: #dc3545; }
.pdf:hover { background: #b02a37; }
</style>

<script>
function exportPDF() {
    window.print();  // Browser print → Save as PDF
}
</script>

</head>
<body>

<!-- ✅ Sidebar -->
<div class="sidebar">
    <div class="sidebar-header"><h2>Admin Panel</h2></div>
    <ul class="sidebar-menu">
        <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a></li>
        <li><a href="categories.php"><i class="fas fa-briefcase"></i>Categories</a></li>
        <li><a class="active"><i class="fas fa-file-alt"></i> Reports</a></li>
        <li><a href="admin_change_password.php"><i class="fas fa-key"></i> Change Password</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

<!-- ✅ Main Content -->
<div class="container">
    <div class="card">

        <div class="card-title">Generate Internship Reports</div>

        <form method="POST">
            <div>
                <label><b>Start Date</b></label><br>
                <input type="date" name="start_date" required>
            </div>
            <div>
                <label><b>End Date</b></label><br>
                <input type="date" name="end_date" required>
            </div>
            <div style="margin-top:22px;">
                <button type="submit" name="filter"><i class="fas fa-filter"></i> Generate</button>
            </div>
        </form>

        <?php if ($reportData && mysqli_num_rows($reportData) > 0): ?>
        <div class="export-buttons">
            <a href="reports.php?export=excel&start=<?= $start ?>&end=<?= $end ?>">
                <button class="export-btn excel"><i class="fas fa-file-excel"></i> Export Excel</button>
            </a>
            <button class="export-btn pdf" onclick="exportPDF()">
                <i class="fas fa-file-pdf"></i> Export PDF
            </button>
        </div>

        <table>
            <tr>
                <th>Internship Title</th>
                <th>Start Date</th>
                <th>Apply By</th>
                <th>Student Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Applied On</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($reportData)) { ?>
            <tr>
                <td><?= $row['title'] ?></td>
                <td><?= $row['start_date'] ?></td>
                <td><?= $row['apply_by'] ?></td>
                <td><?= $row['stu_fname'] . " " . $row['stu_lname'] ?></td>
                <td><?= $row['stu_email'] ?></td>
                <td><?= $row['stu_phone'] ?></td>
                <td><?= $row['applied_on'] ?></td>
            </tr>
            <?php } ?>
        </table>

        <?php elseif ($reportData): ?>
            <p style="color:red; margin-top:15px;">No internship applications found for the selected date range.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
