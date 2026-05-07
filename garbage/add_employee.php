<?php
session_start();
include("conn.php");
if(!isset($_SESSION['adminid'])){
    header("Location: admin_login.php");
    exit();
}

if(isset($_POST['add'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $position = $_POST['position'];

    $stmt = $conn->prepare("INSERT INTO employees (name, email, position) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $position);
    $stmt->execute();
    header("Location: admin_employee.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Employee</title>
</head>
<body>
<h2>Add Employee</h2>
<form method="POST">
    Name: <input type="text" name="name" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Position: <input type="text" name="position" required><br><br>
    <input type="submit" name="add" value="Add Employee">
</form>
<a href="admin_employee.php">Back</a>
</body>
</html>
