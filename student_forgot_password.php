<?php
session_start();
include("conn.php");

if(isset($_POST['submit'])){
    $email = trim($_POST['email']);

    // ✅ Student email check
    $stmt = $conn->prepare("SELECT sid FROM student_info WHERE stu_email=? AND is_deleted=0");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if($row = $res->fetch_assoc()){
        $_SESSION['reset_sid'] = $row['sid'];   // ✅ Store student id
        $_SESSION['reset_type'] = 'student';    // ✅ Identify type

        header("Location: reset_password.php");  
        exit();
    } else {
        echo "<script>alert('❌ Email Not Found');</script>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Forgot Password | Internshop</title>
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
            transition: 0.3s ease;
        }
        input[type=email]:focus {
            border-color: #5563DE;
            box-shadow: 0 0 6px rgba(85,99,222,0.3);
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
            transition: 0.3s ease;
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
            padding: 8px 20px;
            border-radius: 25px;
            background: #6c757d;
            color: #fff;
            font-weight: bold;
            font-size: 13px;
            text-decoration: none;
            transition: 0.2s ease;
        }
        .back-login:hover {
            background: #495057;
            transform: translateY(-1px);
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Forgot Your Password?</h1>
        <p>Please enter your registered student email</p>

        <form method="POST">
            <input type="email" name="email" placeholder="Enter registered student email" required>
            <input type="submit" name="submit" value="Request Reset">
        </form>

        <a href="student_login.php" class="back-login">Back to Login</a>
    </div>
</body>
</html>
