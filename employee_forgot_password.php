<?php
session_start();
include("conn.php");

if(isset($_POST['submit'])){
    $email = trim($_POST['email']);

    // ✅ Only employer logic
    $stmt = $conn->prepare("SELECT eid FROM employer_info WHERE emp_email=? AND is_deleted=0 AND status='active'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if($row = $res->fetch_assoc()){
        $_SESSION['reset_eid'] = $row['eid'];
        $_SESSION['reset_type'] = 'employer';
        header("Location: employee_reset.php");
        exit();
    } else {
        echo "<script>alert('❌ Email Not Found OR Account Not Active');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employer Forgot Password | Internshop</title>
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
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 24px;
        }
        p {
            color: #555;
            margin-bottom: 20px;
            font-size: 14px;
        }
        input[type=email] {
            width: 90%;
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 25px;
            border: 1px solid #ccc;
            outline: none;
            font-size: 14px;
        }
        input[type=submit] {
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
        input[type=submit]:hover {
            background: #3944BC;
        }
        .back-login {
            display: inline-block;
            margin-top: 15px;
            padding: 8px 20px;
            border-radius: 25px;
            background: #6c757d;
            color: #fff;
            font-weight: bold;
            font-size: 13px;
            text-decoration: none;
        }
        .back-login:hover {
            background: #495057;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Forgot Your Password?</h1>
        <p>Please enter your registered employer email</p>

        <form method="POST">
            <input type="email" name="email" placeholder="Enter your registered email" required>
            <input type="submit" name="submit" value="Request Password Reset">
        </form>

        <a href="employer_login.php" class="back-login">Back to Login</a>
    </div>
</body>
</html>
