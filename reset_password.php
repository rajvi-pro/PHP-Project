<?php
session_start();
include("conn.php");

if(!isset($_SESSION['reset_type']) || !isset($_SESSION['reset_sid']) && !isset($_SESSION['reset_eid'])){
    // No reset session, redirect to login
    header("Location: student_login.php");
    exit();
}

$type = $_SESSION['reset_type'];
$id_session = $type == 'student' ? 'reset_sid' : 'reset_eid';

if(isset($_POST['submit'])){
    $new_pass = trim($_POST['new_pass']);
    $confirm_pass = trim($_POST['confirm_pass']);

    if($new_pass !== $confirm_pass){
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        $hash = password_hash($new_pass, PASSWORD_BCRYPT);

        if($type == 'student'){
            $stmt = $conn->prepare("UPDATE student_info SET stu_pass=? WHERE sid=? AND is_deleted=0");
        } else {
            $stmt = $conn->prepare("UPDATE employer_info SET emp_pass=? WHERE eid=? AND is_deleted=0");
        }
        $stmt->bind_param("si", $hash, $_SESSION[$id_session]);
        $stmt->execute();
        $stmt->close();

        unset($_SESSION[$id_session]);
        unset($_SESSION['reset_type']);

        echo "<script>
                alert('Password reset successfully! Please login.');
                window.location.href='" . ($type == 'student' ? 'student_login.php' : 'employer_login.php') . "';
              </script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password | Internshop</title>
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
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            text-align: center;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 24px;
        }
        p {
            color: #555;
            margin-bottom: 25px;
            font-size: 14px;
        }
        input[type=password] {
            width: 90%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ccc;
            outline: none;
            font-size: 14px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }
        input[type=password]:focus {
            border-color: #5563DE;
            box-shadow: 0 0 6px rgba(85,99,222,0.3);
        }
        input[type=submit] {
            width: 95%;
            padding: 14px 0;
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
        .back-login {
            display: inline-block;
            margin-top: 15px;
            padding: 8px 25px;
            border-radius: 25px;
            background: #6c757d;
            color: #fff;
            font-weight: bold;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .back-login:hover {
            background: #495057;
            transform: translateY(-1px);
        }
        label {
            display: block;
            text-align: left;
            margin-left: 5%;
            font-size: 13px;
            margin-bottom: 5px;
            color: #333;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reset Your Password</h1>
        <p>Please enter your new password below</p>
        <form method="POST">
            <label for="new_pass">New Password</label>
            <input type="password" name="new_pass" id="new_pass" placeholder="Enter new password" required>
            
            <label for="confirm_pass">Confirm Password</label>
            <input type="password" name="confirm_pass" id="confirm_pass" placeholder="Confirm new password" required>
            
            <input type="submit" name="submit" value="Reset Password">
        </form>
        <a href="<?= $type == 'student' ? 'student_login.php' : 'employer_login.php' ?>" class="back-login">Back to Login</a>
    </div>
</body>
</html>
