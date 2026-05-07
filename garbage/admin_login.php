<?php
session_start();
include("conn.php");

// Redirect if already logged in
if (isset($_SESSION['adminid'])) {
    header("Location: admin_dashboard.php");
    exit();
}

// Initialize message variable
$msg = '';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));

    if (!empty($username) && !empty($password)) {
        // Fetch admin by username
        $query = "SELECT adminid, password FROM admin WHERE username = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 1) {
            $admin = mysqli_fetch_assoc($result);

            // Verify password
            if (password_verify($password, $admin['password'])) {
                $_SESSION['adminid'] = $admin['adminid'];
                $msg = "<div class='notify success'>Login successful! Redirecting...</div>";
                echo "<script>
                        setTimeout(function(){
                            window.location.href='admin_dashboard.php';
                        }, 1000);
                      </script>";
            } else {
                $msg = "<div class='notify error'>Incorrect password.</div>";
            }
        } else {
            $msg = "<div class='notify error'>Invalid username.</div>";
        }

        mysqli_stmt_close($stmt);
    } else {
        $msg = "<div class='notify error'>Please fill in all fields.</div>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/login_form_style.css">
    <style>
        /* Notification styles like employee login */
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

    <main>
        <div class="login-form-background">
            <div class="login-form-overly">
                <div class="login-form-style">
                    <?php if ($msg) echo $msg; ?>
                    <form method="POST" action="">
                        <div class="login-form">
                            <div class="login-form-text">
                                <h1>Admin Login</h1>
                            </div>
                            <div class="login-form-col">
                                <label class="login-form-label">Username</label><br>
                                <input class="login-form-input" type="text" name="username" placeholder="Enter Username" required><br>
                            </div>
                            <div class="login-form-col">
                                <label class="login-form-label">Password</label><br>
                                <input class="login-form-input" type="password" name="password" placeholder="Enter Password" required><br>
                            </div>
                            <div class="login-sub-btn-hover">
                                <input class="login-form-sub-btn" type="submit" value="Login" name="login">
                            </div>
                            <div class="new-to-internshop">
                                <p>New admin? Contact the system administrator.</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <?php include("footer.php"); ?>
    </footer>
</body>
</html>
