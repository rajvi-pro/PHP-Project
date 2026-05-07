<?php 
session_start();
include("conn.php");

if(isset($_SESSION['sid'])){
    header("Location: student_dashboard.php");
    exit();
}

if(isset($_SESSION['eid'])){
    header("Location: employer_dashboard.php");
    exit();
}
?>

<html>
<head>
    <title>Internshop | Employer Login</title>
    <link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/login_form_style.css">
    <script type="text/javascript" src="script/errorMessage.js"></script>
</head>

<body>
<header>
    <?php include("nav.php"); ?>
</header>

<?php 

$emailRegex = "/^[a-z]{2}(([0-9a-z]+)?\.?([0-9a-z]+)?){0,18}@[A-Za-z]{3,20}\.(com|in|org|edu\.in|gov\.in)$/";
$passRegex = "/^[A-Za-z0-9`~!@#$%^&*]{6,20}$/";

if(isset($_POST['login'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, $_POST['pass']);

    if (!preg_match($emailRegex, $email)){
        echo "<script>showNotify('Invalid email format.',1);</script>";
    }
    else if (!preg_match($passRegex, $pass)){
        echo "<script>showNotify('Invalid password format.',1);</script>";
    }
    else {
        // ✅ Fetch hashed password
        $query = "SELECT eid, emp_fname, emp_pass, status, com_dp_path 
                  FROM employer_info 
                  WHERE emp_email='$email' AND is_deleted = 0";

        $query_run = mysqli_query($conn, $query);

        if(mysqli_num_rows($query_run) > 0)
        {
            $row = mysqli_fetch_assoc($query_run);

            // ✅ verify hashed password
            if(password_verify($pass, $row['emp_pass'])) {

                $_SESSION['eid'] = $row['eid'];
                $_SESSION['N'] = strtoupper(substr($row['emp_fname'], 0, 1));
                $_SESSION['status'] = $row['status'];
                $_SESSION['dp_path'] = $row['com_dp_path'];

                echo "<script>
                        showNotify('Login successful! Redirecting...',2);
                        setTimeout(() => location.replace('employer_dashboard.php'), 1200);
                      </script>";
            }
            else {
                echo "<script>showNotify('Incorrect password.',1);</script>";
            }
        }
        else {
            echo "<script>showNotify('No account found with this email.',1);</script>";
        }
    }
}
?>

<main>
    <div class="login-form-background">
        <div class="login-form-overly">
            <div class="login-form-style">
                <form method="POST">
                    <div class="login-form">
                        <div class="login-form-text">
                            <h1>Company Login</h1>
                        </div>

                        <div class="login-form-col">
                            <label for="login-email" class="login-form-label">Official Email Id</label><br>
                            <input class="login-form-input" id="login-email" type="email"
                                placeholder="e.g. john@company.com" 
                                name="email" required>
                        </div>

                        <div class="login-form-col">
                            <label for="login-pass" class="login-form-label">Password</label><br>
                            <input class="login-form-input" id="login-pass" type="password"
                                placeholder="Enter password" name="pass" required>
                        </div>

                        <div class="login-form-col">
                            <a href="employee_forgot_password.php">Forgot password?</a>
                        </div>

                        <div class="login-sub-btn-hover">
                            <input class="login-form-sub-btn" type="submit" value="Login" name="login">
                        </div>

                        <div class="new-to-internshop">
                            <p>New to Internshop? Register (<a href="student_register.php">Student</a> /
                                <a href="employer_register.php">Company</a>)
                            </p>
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

<script src="script/script.js"></script>
</body>
</html>
