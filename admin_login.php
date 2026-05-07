<?php
session_start();
include("conn.php");

// Redirect if already logged in
if (isset($_SESSION['adminid'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$msg = '';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));

    if (!empty($username) && !empty($password)) {
        $query = "SELECT adminid, password FROM admin WHERE username = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 1) {
            $admin = mysqli_fetch_assoc($result);

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
    <title>Admin Login | Internshop</title>
    <link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f2f2f2;
        }

        .login-form-background {
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('images/login.png');
            background-size: cover;
            background-position: center;
            height: 100vh;
        }

        .login-form-overly {
            background: rgba(0, 0, 0, 0.4);
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-form-style {
            background: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0,0,0,0.2);
            width: 380px;
        }

        .login-form-style h1 {
            text-align: center;
            color: #008BDC;
            margin-bottom: 25px;
        }

        .login-form-col {
            margin-bottom: 20px;
        }

        .login-form-label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            color: #484848;
            margin-bottom: 5px;
        }

        .login-form-input {
            width: 100%;
            height: 40px;
            border-radius: 4px;
            border: 1px solid #ddd;
            padding-left: 12px;
            font-size: 15px;
        }

        .login-form-input:hover {
            border-color: #008BDC;
        }

        .login-form-sub-btn {
            width: 100%;
            height: 40px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            color: #fff;
            background: #008BDC;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-form-sub-btn:hover {
            background: #005c99;
        }

        .notify {
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
        }

        .notify.success {
            background-color: #c7f7d4;
            color: #008000;
        }

        .notify.error {
            background-color: #f7d4d4;
            color: #b30000;
        }

        .new-to-internshop {
            text-align: center;
            margin-top: 15px;
            color: #495057;
            font-size: 14px;
        }

        .new-to-internshop a {
            color: #008BDC;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!--header-->
		<header>
			<?php include("nav.php"); ?>
		</header>

			

<div class="login-form-background">
    <div class="login-form-overly">
        <div class="login-form-style">
            <?php if ($msg) echo $msg; ?>
            <form method="POST" action="">
                <h1>Admin Login</h1>
                <div class="login-form-col">
                    <label class="login-form-label">Username</label>
                    <input class="login-form-input" type="text" name="username" placeholder="Enter Username" required>
                </div>
                <div class="login-form-col">
                    <label class="login-form-label">Password</label>
                    <input class="login-form-input" type="password" name="password" placeholder="Enter Password" required>
                </div>
                <div>
                    <input class="login-form-sub-btn" type="submit" value="Login" name="login">
                </div>
                <div class="new-to-internshop">
                    <p>New admin? Contact the system administrator.</p>
                </div>
            </form>
        </div>
    </div>
</div>
<footer>
			<?php include("footer.php"); ?>
		</footer>

</body>
</html>
