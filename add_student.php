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

    $stmt = $conn->prepare("INSERT INTO students (name, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $email);
    $stmt->execute();
    header("Location: admin_students.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head><title>Add Student</title></head>
<body>
<h2>Add Student</h2>
<form method="POST">
    Name: <input type="text" name="name" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    <input type="submit" name="add" value="Add Student">
</form>
<a href="admin_students.php">Back</a>
</body>
</html>
