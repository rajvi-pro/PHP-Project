<?php
session_start();
include("conn.php");

// Redirect if not logged in
if (!isset($_SESSION['adminid'])) {
    header("Location: admin_login.php");
    exit();
}

$adminid = $_SESSION['adminid'];
$msg = '';

// Fetch admin details
$query = "SELECT username, email FROM admin WHERE adminid = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $adminid);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$admin = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Update profile (username/email)
if (isset($_POST['update_profile'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    if (!empty($username) && !empty($email)) {
        $update = "UPDATE admin SET username = ?, email = ? WHERE adminid = ?";
        $stmt = mysqli_prepare($conn, $update);
        mysqli_stmt_bind_param($stmt, "ssi", $username, $email, $adminid);
        if (mysqli_stmt_execute($stmt)) {
            $msg = "<div class='notify success'>Profile updated successfully!</div>";
        } else {
            $msg = "<div class='notify error'>Error updating profile.</div>";
        }
        mysqli_stmt_close($stmt);
    } else {
        $msg = "<div class='notify error'>Please fill all fields.</div>";
    }
}

// Change password
if (isset($_POST['change_password'])) {
    $current = trim($_POST['current_password']);
    $new = trim($_POST['new_password']);
    $confirm = trim($_POST['confirm_password']);

    if (!empty($current) && !empty($new) && !empty($confirm)) {
        $check = "SELECT password FROM admin WHERE adminid = ?";
        $stmt = mysqli_prepare($conn, $check);
        mysqli_stmt_bind_param($stmt, "i", $adminid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $adminData = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if (password_verify($current, $adminData['password'])) {
            if ($new === $confirm) {
                $hashed = password_hash($new, PASSWORD_DEFAULT);
                $update = "UPDATE admin SET password = ? WHERE adminid = ?";
                $stmt = mysqli_prepare($conn, $update);
                mysqli_stmt_bind_param($stmt, "si", $hashed, $adminid);
                if (mysqli_stmt_execute($stmt)) {
                    $msg = "<div class='notify success'>Password changed successfully!</div>";
                } else {
                    $msg = "<div class='notify error'>Error updating password.</div>";
                }
                mysqli_stmt_close($stmt);
            } else {
                $msg = "<div class='notify error'>New passwords do not match.</div>";
            }
        } else {
            $msg = "<div class='notify error'>Current password is incorrect.</div>";
        }
    } else {
        $msg = "<div class='notify error'>Please fill all fields.</div>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Settings</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/login_form_style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
        }
        .settings-container {
            width: 50%;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 25px;
        }
        form {
            margin-bottom: 30px;
        }
        label {
            font-weight: bold;
        }
        input[type=text],
        input[type=email],
        input[type=password] {
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        input[type=submit] {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type=submit]:hover {
            background-color: #2563eb;
        }
        .notify {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-weight: bold;
        }
        .notify.success { background-color: #d4edda; color: #155724; }
        .notify.error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
<header>
    <?php include("nav.php"); ?>
</header>

<div class="settings-container">
    <h2>Admin Settings</h2>
    <?php if ($msg) echo $msg; ?>

    <form method="POST">
        <h3>Update Profile</h3>
        <label>Username</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" required>
        <label>Email</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required>
        <input type="submit" name="update_profile" value="Update Profile">
    </form>

    <form method="POST">
        <h3>Change Password</h3>
        <label>Current Password</label>
        <input type="password" name="current_password" required>
        <label>New Password</label>
        <input type="password" name="new_password" required>
        <label>Confirm New Password</label>
        <input type="password" name="confirm_password" required>
        <input type="submit" name="change_password" value="Change Password">
    </form>
</div>

<footer>
    <?php include("footer.php"); ?>
</footer>
</body>
</html>
