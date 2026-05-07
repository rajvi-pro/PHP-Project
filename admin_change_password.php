<?php
session_start();
include("conn.php");  // DB connection

// Check admin login
if (!isset($_SESSION['adminid'])) {
    header("Location: index.php");
    exit();
}

$adminid = $_SESSION['adminid'];

if (isset($_POST['submit'])) {
    $new_pass = mysqli_real_escape_string($conn, $_POST['new_pass']);
    $confirm_pass = mysqli_real_escape_string($conn, $_POST['confirm_pass']);

    if ($new_pass !== $confirm_pass) {
        echo "<script>alert('❌ New & Confirm password do not match');</script>";
    } else {
        $hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE admin SET password='$hashed_password' WHERE adminid='$adminid'");
        echo "<script>
                alert('✅ Password Updated Successfully!');
                window.location = 'admin_dashboard.php';
              </script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Admin Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #5563DE, #74ABE2);
        }

        .password-container {
            background: #fff;
            padding: 40px 50px;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            width: 400px;
            text-align: center;
        }

        .password-container h2 {
            margin-bottom: 30px;
            font-weight: 600;
            color: #333;
        }

        .password-container .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .password-container .input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #444;
        }

        .password-container .input-group input {
            width: 100%;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
            outline: none;
            font-size: 14px;
            transition: 0.3s;
        }

        .password-container .input-group input:focus {
            border-color: #5563DE;
            box-shadow: 0 0 8px rgba(85,99,222,0.3);
        }

        .password-container button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background-color: #5563DE;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .password-container button:hover {
            background-color: #3f4cb7;
        }

        .password-container .fa-lock {
            margin-right: 8px;
            color: #5563DE;
        }
    </style>
</head>
<body>

<div class="password-container">
    <h2><i class="fa-solid fa-lock"></i> Change Password</h2>
    <form method="POST">
        <div class="input-group">
            <label><i class="fa-solid fa-key"></i> Old Password</label>
            <input type="password" name="new_pass" placeholder="Enter New Password" required>
        </div>
  
    <div class="input-group">
            <label><i class="fa-solid fa-key"></i> New Password</label>
            <input type="password" name="new_pass" placeholder="Enter New Password" required>
        </div>
        <div class="input-group">
            <label><i class="fa-solid fa-key"></i> Confirm Password</label>
            <input type="password" name="confirm_pass" placeholder="Confirm New Password" required>
        </div>
        <button type="submit" name="submit"><i class="fa-solid fa-floppy-disk"></i> Save Password</button>
    </form>
</div>

</body>
</html>
