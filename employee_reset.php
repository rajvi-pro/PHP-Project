<?php
session_start();
include("conn.php"); // ✅ make sure conn.php has correct DB credentials

// Redirect if session not set
if(!isset($_SESSION['reset_eid']) || $_SESSION['reset_type'] != 'employer'){
    header("Location: employee_forgot_password.php");
    exit();
}

$eid = $_SESSION['reset_eid'];

if(isset($_POST['submit'])){
    $new_pass = trim($_POST['new_pass']);
    $confirm_pass = trim($_POST['confirm_pass']);

    if($new_pass !== $confirm_pass){
        $error = "Passwords do not match!";
    } else {
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE employer_info SET emp_pass=? WHERE eid=?");
        $stmt->bind_param("si", $hashed_pass, $eid);

        if($stmt->execute()){
            // Clear session and show success
            unset($_SESSION['reset_eid']);
            unset($_SESSION['reset_type']);
            $success = "✅ Password updated successfully! <a href='employer_login.php'>Login here</a>";
        } else {
            $error = "❌ Failed to update password!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password - Employer</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #74ABE2, #5563DE);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            width: 350px;
            background: #fff;
            padding: 35px 25px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            text-align: center;
        }
        h2 { margin-bottom: 20px; color: #333; }
        input[type=password] {
            width: 90%;
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 25px;
            border: 1px solid #ccc;
            outline: none;
            font-size: 14px;
        }
        button {
            width: 95%;
            padding: 12px 20px;
            border: none;
            border-radius: 25px;
            background: #5563DE;
            color: #fff;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover { background: #3944BC; }
        .message { margin-bottom: 15px; }
        .message.error { color: red; }
        .message.success { color: green; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <?php 
            if(isset($error)) echo "<p class='message error'>$error</p>";
            if(isset($success)) echo "<p class='message success'>$success</p>";
        ?>
        <form method="POST">
            <input type="password" name="new_pass" placeholder="New Password" required><br>
            <input type="password" name="confirm_pass" placeholder="Confirm Password" required><br><br>
            <button type="submit" name="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
