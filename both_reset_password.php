<?php
session_start();
include("conn.php");

// ✅ If reset type is not set, redirect user
if(!isset($_SESSION['reset_type'])){
    header("Location: student_login.php");
    exit();
}

$type = $_SESSION['reset_type'];
$id_session = $type == 'student' ? 'reset_sid' : 'reset_eid';

if(isset($_POST['submit'])){
    $new_pass = trim($_POST['new_password']);
    $confirm_pass = trim($_POST['confirm_password']);

    if($new_pass !== $confirm_pass){
        echo "<script>alert('❌ Passwords do not match!');</script>";
    } else {
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);

        if($type == 'student'){
            $stmt = $conn->prepare("UPDATE student_info SET stu_pass=? WHERE sid=?");
        } else {
            $stmt = $conn->prepare("UPDATE employer_info SET emp_pass=? WHERE eid=?");
        }

        $stmt->bind_param("si", $hashed_pass, $_SESSION[$id_session]);
        $stmt->execute();

        unset($_SESSION['reset_type']);
        unset($_SESSION[$id_session]);

        echo "<script>alert('✅ Password Updated Successfully!'); 
        window.location.href='student_login.php';</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password | Internshop</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1e2a78, #a83279);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .reset-container {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.15);
            padding: 40px 35px;
            border-radius: 12px;
            width: 380px;
            color: white;
            text-align: center;
            box-shadow: 0px 0px 25px rgba(0,0,0,0.2);
        }

        h2 {
            margin-bottom: 25px;
        }

        .input-group {
            width: 100%;
            margin-bottom: 18px;
            position: relative;
        }

        input {
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            border: none;
            outline: none;
            font-size: 16px;
            padding-right: 40px;
        }

        .toggle-eye {
            position: absolute;
            right: 12px;
            top: 12px;
            cursor: pointer;
            color: #333;
            font-size: 14px;
        }

        .btn {
            width: 100%;
            background: #ffcb3d;
            padding: 12px;
            font-size: 17px;
            font-weight: bold;
            border-radius: 6px;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background: #ffb400;
        }

        a {
            display: block;
            margin-top: 15px;
            color: #fff;
            text-decoration: none;
            opacity: .9;
        }

        a:hover {
            opacity: 1;
        }
    </style>
</head>

<body>
<div class="reset-container">
    <h2>🔒 Reset Your Password</h2>

    <form method="POST">
        <div class="input-group">
            <input type="password" name="new_password" id="newPass" placeholder="Enter New Password" required>
            <span class="toggle-eye" onclick="togglePassword('newPass')">👁</span>
        </div>

        <div class="input-group">
            <input type="password" name="confirm_password" id="confirmPass" placeholder="Confirm Password" required>
            <span class="toggle-eye" onclick="togglePassword('confirmPass')">👁</span>
        </div>

        <button type="submit" name="submit" class="btn">Update Password</button>
    </form>

    <a href="student_login.php">⬅ Back to Login</a>
</div>

<script>
function togglePassword(id){
    let field = document.getElementById(id);
    field.type = field.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
