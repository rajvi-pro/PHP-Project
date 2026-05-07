<?php 
	session_start();
	include("conn.php");

	if(isset($_SESSION['sid'])){
		header('Location: student_dashboard.php');
		exit();
	}else if(isset($_SESSION['eid'])){
		header('Location: employer_dashboard.php');
		exit();
	}
?>

<!--Student Register Form-->
<html>
<head>
	<title>Internshop | Student Register</title>
	<link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
	<link rel="stylesheet" type="text/css" href="css/student_reg_style.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<script type="text/javascript" src="script/errorMessage.js"></script>
</head>
<body>
	<header>
		<?php include("nav.php"); ?>
	</header>

	<?php 
		$nameRegex = "/^[A-Za-z]{2,20}$/";
		$emailRegex = "/^[a-z]{2}(([0-9a-z]+)?\.?([0-9a-z]+)?){0,18}@gmail\.com$/";
		$passRegex = "/^[A-Za-z0-9`~!@#$%^&*]{6,20}$/";
		$phoneRegex = "/^[789][0-9]{9}$/";

		if(isset($_POST['signup']) && !empty($_POST['signup'])){

			// Creating all variables from POST data
			foreach($_POST as $key => $value){
			    $$key = mysqli_real_escape_string($conn, $value);
			}

			if(!preg_match($emailRegex, $email)){
				$msg = "<script>showNotify('Invalid email.',1);</script>";
			}else if (!preg_match($nameRegex, $fname)) {
				$msg = "<script>showNotify('Invalid first name.',1);</script>";
			}else if (!preg_match($nameRegex, $lname)) {
				$msg = "<script>showNotify('Invalid last name.',1);</script>";
			}else if (!preg_match($passRegex, $pass)) {
				$msg = "<script>showNotify('Invalid password.',1);</script>";
			}else if (!preg_match($phoneRegex, $phno)){
				$msg = "<script>showNotify('Invalid phone no.',1);</script>";
			}else{

				// Check if email already exists and is not soft-deleted
				$query = "SELECT stu_email FROM student_info WHERE stu_email='$email' AND status!='deleted'";
				$result = mysqli_query($conn, $query);
				$num = mysqli_num_rows($result);

				$query1 = "SELECT emp_email FROM employer_info WHERE emp_email='$email'";
				$result1 = mysqli_query($conn, $query1);
				$num1 = mysqli_num_rows($result1);

				if ($num>0 || $num1>0){
					$msg = "<script>showNotify('Email already exists.',1);</script>";
				}else{
					// Hash the password
					$hashedPass = password_hash($pass, PASSWORD_DEFAULT);

					// Register user with status 'active'
					$query2 = "INSERT INTO student_info(stu_fname, stu_lname, stu_email, stu_phone, stu_pass, status) 
							   VALUES('$fname', '$lname', '$email', '$phno', '$hashedPass', 'active')";
					$result2 = mysqli_query($conn, $query2);

					if($result2){
						$msg = "<script>showNotify('Registration successful. Redirecting to Login...',2);
									setTimeout(function(){
										location.replace('student_login.php');	
									},2000);								
						</script>";
					}else{
						$msg = "<script>showNotify('Registration failed.',1);</script>";
					}
				}
			}
			echo $msg;
		}					
	?>

	<main>
		<div class="reg-background">
			<div class="reg-overly">
				<div class="sign-up-label">
					<h2>Sign-up and apply for free</h2>
					<p>1,50,000+ companies hiring on Internshop</p>
				</div>
				
				<!--Student Register form-->
				<div class="reg-style">
					<form method="POST" action="" id="reg-form">
						<div class="form">
							<div class="register-text">
								<div id="msg"></div>
								<h1>Student Registration</h1>
							</div>
							<div class="r-input">
								<div class="r-f-input">
									<label for="first" class="r-label">First Name</label><br>
									<input class="reg-input" id="first" type="text" placeholder="e.g. John" name="fname" required pattern="^[A-Za-z]{2,30}$" value="<?php if(isset($_POST['fname'])){echo $_POST['fname'];}?>">
								</div>
								<div class="r-input">
									<label for="last" class="r-label">Last Name</label><br>
									<input class="reg-input" id="last" type="text" placeholder="e.g. Wick" name="lname" required pattern="^[A-Za-z]{2,30}$" value="<?php if(isset($_POST['lname'])){echo $_POST['lname'];}?>">
								</div>
							</div>
							<div class="r-input">
								<label for="email" class="r-label">Email</label><br>
								<input class="reg-input" id="email" type="email" placeholder="e.g. john@gmail.com" name="email" pattern="^[a-z]{2}(([0-9a-z]+)?\.?([0-9a-z]+)?){0,18}@gmail\.com$" required value="<?php if(isset($_POST['email'])){echo $_POST['email'];}?>"><br>
							</div>
							<div class="r-input">
								<label for="num" class="r-label">Phone Number</label><br>
								<input class="reg-input" id="num" type="text" placeholder="10 digit Mobile number" name="phno" required pattern="^[789][0-9]{9}$" value="<?php if(isset($_POST['phno'])){echo $_POST['phno'];}?>"><br>
							</div>
							<div class="r-input">
								<label for="pass" class="r-label">Password</label><br>
								<input class="reg-input" id="pass" type="password" placeholder="Must be at least 6 characters" name="pass" pattern="^[A-Za-z0-9`~!@#$%^&*]{6,20}$" required><br>
							</div>
							<div class="r-input">
								<label for="cpass" class="r-label">Confirm Password</label><br>
								<input class="reg-input" id="cpass" type="password" placeholder="Must be at least 6 characters" name="cpass" pattern="^[A-Za-z0-9`~!@#$%^&*]{6,20}$" required><br>
							</div>
							<div class="ter-con">
								<p>By signing up, you agree to our <a href="#">Terms and Conditions</a>.</p><br>
							</div>
							<div class="reg-sub-btn-hover">
								<input class="reg-sub-btn" type="submit" value="Sign-up" name="signup">
							</div>
							<div class="r-new-to-internshop">
								<p>Already registered? <a href="student_login.php">Login</a></p>
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
