<?php
session_start();
include("conn.php");
if(!isset($_SESSION['adminid'])){
    header("Location: admin_login.php");
    exit();
}

$sid = $_GET['sid'];
$result = $conn->query("SELECT * FROM students WHERE sid=$sid");
$student = $result->fetch_assoc();

if(isset($_POST['update'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $stmt = $conn->prepare("UPDATE students SET name=?, email=? WHERE sid=?");
    $stmt->bind_param("ssi", $name, $email, $sid);
    $stmt->execute();
    header("Location: admin_students.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head><title>Edit Student</title></head>
<body>
<h2>Edit Student</h2>
<form method="POST">
    Name: <input type="text" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required><br><br>
    Email: <input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required><br><br>
    <input type="submit" name="update" value="Update Student">
</form>
<a href="admin_students.php">Back</a>
</body>
</html>
