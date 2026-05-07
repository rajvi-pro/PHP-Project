<?php
session_start();
include("conn.php");
if(!isset($_SESSION['adminid'])){
    header("Location: admin_login.php");
    exit();
}

$eid = $_GET['eid'];
$result = $conn->query("SELECT * FROM employees WHERE eid=$eid");
$employee = $result->fetch_assoc();

if(isset($_POST['update'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $position = $_POST['position'];

    $stmt = $conn->prepare("UPDATE employees SET name=?, email=?, position=? WHERE eid=?");
    $stmt->bind_param("sssi", $name, $email, $position, $eid);
    $stmt->execute();
    header("Location: admin_employee.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Employee</title>
</head>
<body>
<h2>Edit Employee</h2>
<form method="POST">
    Name: <input type="text" name="name" value="<?php echo htmlspecialchars($employee['name']); ?>" required><br><br>
    Email: <input type="email" name="email" value="<?php echo htmlspecialchars($employee['email']); ?>" required><br><br>
    Position: <input type="text" name="position" value="<?php echo htmlspecialchars($employee['position']); ?>" required><br><br>
    <input type="submit" name="update" value="Update Employee">
</form>
<a href="admin_employee.php">Back</a>
</body>
</html>
