<?php
session_start();
include("conn.php");

// Redirect if not admin
if (!isset($_SESSION['adminid'])) {
    header("Location: index.php");
    exit();
}

// Example data (replace these with DB queries)
$companies = ["Techify Pvt Ltd", "GrowLab", "DesignWorks"];
$categories = ["Web Development", "Graphic Design", "Marketing", "Finance"];

// Example existing internships
$internships = [
    ["Techify Pvt Ltd", "Web Developer Intern", "Web Development", "2025-09-01", "2025-12-01", "Active"],
    ["GrowLab", "Marketing Intern", "Marketing", "2025-08-15", "2025-11-15", "Closed"],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin | Internship Management</title>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }
    body {
        display: flex;
        background-color: #f4f4f4;
        height: 100vh;
    }

    /* Sidebar */
    .sidebar {
        width: 250px;
        background: #2f2f2f;
        color: white;
        height: 100vh;
        position: fixed;
        overflow-y: auto;
    }
    .sidebar h2 {
        text-align: center;
        padding: 20px 0;
        background: #3f3f3f;
        font-size: 18px;
        letter-spacing: 1px;
    }
    .sidebar ul {
        list-style: none;
    }
    .sidebar ul li {
        padding: 15px 20px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .sidebar ul li a {
        color: white;
        text-decoration: none;
        display: block;
        font-size: 15px;
        transition: 0.3s;
    }
    .sidebar ul li:hover, .sidebar ul li a.active {
        background: #5e5ef7;
    }

    /* Main content */
    .main-content {
        margin-left: 250px;
        padding: 25px;
        width: calc(100% - 250px);
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .header h1 {
        font-size: 22px;
        color: #333;
    }
    .admin-info {
        background: #5e5ef7;
        color: white;
        padding: 10px 20px;
        border-radius: 20px;
        font-size: 14px;
    }

    /* Add Internship Form */
    .form-section {
        background: white;
        margin-top: 30px;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .form-section h2 {
        margin-bottom: 20px;
        color: #444;
        font-size: 20px;
    }
    form {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        color: #333;
    }
    input, select, textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
    }
    textarea {
        grid-column: 1 / span 2;
        resize: none;
        height: 80px;
    }
    .btn-submit {
        grid-column: 1 / span 2;
        background: #5e5ef7;
        color: white;
        border: none;
        padding: 12px;
        font-size: 16px;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.3s;
    }
    .btn-submit:hover {
        background: #4444d8;
    }

    /* Internship Table */
    .internship-list {
        margin-top: 30px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ccc;
    }
    th {
        background: #5e5ef7;
        color: white;
    }
    td a {
        text-decoration: none;
        color: #5e5ef7;
        font-weight: 600;
    }
    td a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>
    <div class="sidebar">
        <h2>ADMIN PANEL</h2>
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="admin_connections.php">Connections</a></li>
            <li><a href="admin_internship_management.php" class="active">Internship Management</a></li>
            <li><a href="#">Employer Management</a></li>
            <li><a href="#">Job Management</a></li>
            <li><a href="#">Student Management</a></li>
            <li><a href="#">Reporting</a></li>
            <li><a href="#">Settings</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Internship Management</h1>
            <div class="admin-info">Logged in as Admin</div>
        </div>

        <!-- Add Internship Form -->
        <div class="form-section">
            <h2>Add New Internship</h2>
            <form method="post" action="">
                <div>
                    <label>Company Name</label>
                    <select name="company">
                        <option disabled selected>-- Select Company --</option>
                        <?php foreach ($companies as $c) { echo "<option>$c</option>"; } ?>
                    </select>
                </div>
                <div>
                    <label>Internship Title</label>
                    <input type="text" name="title" placeholder="e.g., Web Developer Intern">
                </div>
                <div>
                    <label>Category</label>
                    <select name="category">
                        <option disabled selected>-- Select Category --</option>
                        <?php foreach ($categories as $cat) { echo "<option>$cat</option>"; } ?>
                    </select>
                </div>
                <div>
                    <label>Duration (in months)</label>
                    <input type="number" name="duration" placeholder="e.g., 3">
                </div>
                <div>
                    <label>Start Date</label>
                    <input type="date" name="start_date">
                </div>
                <div>
                    <label>End Date</label>
                    <input type="date" name="end_date">
                </div>
                <textarea name="description" placeholder="Enter internship description..."></textarea>
                <button type="submit" class="btn-submit">Add Internship</button>
            </form>
        </div>

        <!-- Internship List -->
        <div class="internship-list">
            <h2>Existing Internships</h2>
            <table>
                <tr>
                    <th>Company</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($internships as $i) { ?>
                    <tr>
                        <td><?= $i[0] ?></td>
                        <td><?= $i[1] ?></td>
                        <td><?= $i[2] ?></td>
                        <td><?= $i[3] ?></td>
                        <td><?= $i[4] ?></td>
                        <td style="color:<?= ($i[5]=='Active')?'green':'red' ?>;"><?= $i[5] ?></td>
                        <td><a href="#">Edit</a> | <a href="#">Delete</a></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</body>
</html>
