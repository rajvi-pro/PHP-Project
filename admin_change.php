<?php
session_start();

// Include database connection
include("conn.php"); // Make sure conn.php defines $conn as mysqli connection

// Ensure admin is logged in
if(!isset($_SESSION['adminid'])){
    header("Location: index.php");
    exit();
}

// Initialize message
$msg = "";

// Handle form submission
if(isset($_POST['submit'])){
    $current_pass = trim($_POST['current_pass']);
    $new_pass = trim($_POST['new_pass']);
    $confirm_pass = trim($_POST['confirm_pass']);

    // Prepare and execute query to fetch admin password and soft delete status
    $stmt = $conn->prepare("SELECT password, is_deleted FROM admin WHERE adminId=?");
    $stmt->bind_param("i", $_SESSION['adminid']);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();

    if(!$row){
        $msg = "Admin account not found!";
    } else if($row['is_deleted'] == 1){
        $msg = "Your account has been deleted. Password change not allowed!";
    } else if(!password_verify($current_pass, $row['password'])){
        $msg = "Current password is incorrect!";
    } else if($new_pass !== $confirm_pass){
        $msg = "New passwords do not match!";
    } else if(strlen($new_pass) < 6){
        $msg = "Password must be at least 6 characters!";
    } else {
        // Hash new password and update
        $hashed = password_hash($new_pass, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE admin SET password=? WHERE adminId=?");
        $stmt->bind_param("si", $hashed, $_SESSION['adminid']);
        if($stmt->execute()){
            $msg = "Password changed successfully!";
        } else {
            $msg = "Failed to update password. Please try again!";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Change Password | Internshop</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #74ABE2, #5563DE);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            width: 400px;
            background: #fff;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            text-align: center;
        }
        h1 { color: #333; margin-bottom: 20px; }
        input[type=password] {
            width: 90%;
            padding: 12px 15px;
            margin-bottom: 15px;
            border-radius: 25px;
            border: 1px solid #ccc;
            outline: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        input[type=password]:focus { border-color: #5563DE; box-shadow: 0 0 6px rgba(85,99,222,0.3); }
        input[type=submit] {
            width: 95%;
            padding: 14px 20px;
            border: none;
            border-radius: 25px;
            background: #5563DE;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(85,99,222,0.3);
        }
        input[type=submit]:hover {
            background: #3944BC;
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(85,99,222,0.4);
        }
        .message { margin-bottom: 15px; font-weight: bold; color: red; }
        .back-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 8px 20px;
            border-radius: 25px;
            background: #6c757d;
            color: #fff;
            font-weight: bold;
            text-decoration: none;
            font-size: 13px;
            transition: all 0.3s ease;
        }
        .back-btn:hover { background: #495057; transform: translateY(-1px); }
    </style>
</head>
<body>
    <div class="container">
        <h1>Change Password</h1>
        <?php if($msg) echo "<div class='message'>$msg</div>"; ?>
        <form method="POST">
            <input type="password" name="current_pass" placeholder="Current Password" required>
            <input type="password" name="new_pass" placeholder="New Password" required>
            <input type="password" name="confirm_pass" placeholder="Confirm New Password" required>
            <input type="submit" name="submit" value="Change Password">
        </form>
        <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>
