<?php
session_start();
include("conn.php");
if(!isset($_SESSION['adminid'])){
    header("Location: admin_login.php");
    exit();
}

$sql = "SELECT sid, name, email FROM students ORDER BY name ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Students</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f3703a; color: white; }
        tr:nth-child(even){ background-color: #f9f9f9; }
        tr:hover { background-color: #ffe5d0; }
        a { text-decoration: none; }
        .btn { padding: 5px 10px; border-radius: 4px; color: white; background: #f3703a; margin: 2px; }
        .btn:hover { background: #d85c1b; }
    </style>
</head>
<body>
<h2>Students List</h2>
<a class="btn" href="admin_dashboard.php">Dashboard</a>
<a class="btn" href="add_student.php">Add Student</a>
<a class="btn" href="admin_logout.php">Logout</a><br><br>

<?php
if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Actions</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['sid']) . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>
                <a class='btn' href='edit_student.php?sid=".$row['sid']."'>Edit</a>
                <a class='btn' href='delete_student.php?sid=".$row['sid']."' onclick='return confirm(\"Are you sure?\")'>Delete</a>
              </td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No students found.";
}
$conn->close();
?>
</body>
</html>
