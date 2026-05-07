<?php 
session_start();
include("conn.php");

// Redirect if already logged in
if(isset($_SESSION['sid'])){
    header('Location: student_dashboard.php');
    exit();
} else if(isset($_SESSION['eid'])){
    header('Location: employer_dashboard.php');
    exit();
}

// Regex for validation
$emailRegex = "/^[a-z]{2}(([0-9a-z]+)?\.?([0-9a-z]+)?){0,18}@gmail\.com$/";
$passRegex = "/^[A-Za-z0-9`~!@#$%^&*]{6,20}$/";

// Handle login form submission
if(isset($_POST['login']) && !empty($_POST['login'])){
    foreach($_POST as $key => $value){
        $$key = mysqli_real_escape_string($conn,$value);
    }

    if(preg_match($emailRegex, $email) && preg_match($passRegex, $pass)){
        // Fetch user by email (single query)
        $query = "SELECT * FROM student_info WHERE stu_email='$email' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);

            // Verify hashed password
            if(password_verify($pass, $row['stu_pass'])){
                if($row['status'] == 'active'){
                    // Successful login
                    $_SESSION['sid'] = $row['sid'];
                    $_SESSION['N'] = strtoupper(substr($row['stu_fname'], 0, 1));
                    $_SESSION['name'] = $row['stu_fname']." ".$row['stu_lname'];
                    $_SESSION['email'] = $row['stu_email'];
                    $_SESSION['dp_path'] = $row['dp_path'];

                    // Direct redirect
                    header("Location: student_profile.php");
                    exit();
                } else {
                    echo "<script>alert('Your account is deactivated. Please reset your password to reactivate.');</script>";
                }
            } else {
                echo "<script>alert('Invalid email or password.');</script>";
            }
        } else {
            echo "<script>alert('Invalid email or password.');</script>";
        }
    }
}
?>

<html>
<head>
    <title>Internshop | Student Login</title>
    <link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/login_form_style.css">
</head>
<body>

<header>
    <?php include("nav.php"); ?>
</header>

<main>
    <div class="login-form-background">
        <div class="login-form-overly">
            <div class="login-form-style">
                <form action="" method="POST">
                    <div class="login-form">
                        <div class="login-form-text">
                            <h1>Student Login</h1>
                        </div>
                        <div class="login-form-col">
                            <label for="login-email" class="login-form-label">Email</label><br>
                            <input class="login-form-input" id="login-email" type="email" placeholder="e.g. john@gmail.com" name="email" pattern="^[a-z]{2}(([0-9a-z]+)?\.?([0-9a-z]+)?){0,18}@gmail\.com$" value="<?php if(isset($_POST['email'])){echo $_POST['email'];}?>" required><br>
                        </div>
                        <div class="login-form-col">
                            <label for="login-pass" class="login-form-label">Password</label><br>
                            <input class="login-form-input" id="login-pass" type="password" placeholder="Must be at least 6 characters" name="pass" pattern="^[A-Za-z0-9`~!@#$%^&*]{6,20}$" required><br>
                        </div>
                        <div class="login-form-col">
                            <a href="student_forgot_password.php">Forgot password?</a><br>
                        </div>
                        <div class="login-sub-btn-hover">
                            <input class="login-form-sub-btn" type="submit" value="Login" name="login">
                        </div>
                        <div class="new-to-internshop">
                            <p>New to Internshop? Register (<a href="student_register.php">Student</a> / <a href="employer_register.php">Company</a>)</p>
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
