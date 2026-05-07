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

<!--Employer Register Form-->
<html>
<head>
	<title>Internshop | Employer Register</title>
	<link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
	<link rel="stylesheet" type="text/css" href="css/employer_reg_style.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<script type="text/javascript" src="script/errorMessage.js"></script>
</head>
<body>
	<header>
		<?php include("nav.php"); ?>
	</header>

	<?php 
		$nameRegex = "/^[A-Za-z]{2,20}$/";
		$emailRegex = "/^[a-z]{2}(([0-9a-z]+)?\.?([0-9a-z]+)?){0,18}@[A-Za-z]{3,20}\.(com|in|org|edu\.in|gov\.in)$/";
		$passRegex = "/^[A-Za-z0-9`~!@#$%^&*]{6,20}$/";
		$phoneRegex = "/^[789][0-9]{9}$/";

		if(isset($_POST['signup']) && !empty($_POST['signup'])){

			foreach($_POST as $key => $value){
	            $$key = mysqli_real_escape_string($conn,$value);
	        }

			if (!preg_match($emailRegex, $email)){
				$msg = "<script>showNotify('Invalid email.',1);</script>";
			}else if (!preg_match($nameRegex, $fname)) {
				$msg = "<script>showNotify('Invalid first name.',1);</script>";
			}else if (!preg_match($nameRegex, $lname)) {
				$msg = "<script>showNotify('Invalid last name.',1);</script>";
			}else if (!preg_match($passRegex, $pass)) {
				$msg = "<script>showNotify('Invalid password.',1);</script>";
			}else if (!preg_match($phoneRegex,$phno)){
				$msg = "<script>showNotify('Invalid phone no.',1);</script>";
			}else{

				// Check if email exists and is not soft-deleted
				$query = "SELECT stu_email FROM student_info WHERE stu_email='$email'";
				$result = mysqli_query($conn, $query);
				$num = mysqli_num_rows($result);

				$query1 = "SELECT emp_email FROM employer_info WHERE emp_email='$email' AND status!='deleted'";
				$result1 = mysqli_query($conn, $query1);
				$num1 = mysqli_num_rows($result1);

				if ($num>0 OR $num1>0){
					$msg = "<script>showNotify('Email already exists.',1);</script>";
				}else{
					// Hash the password
					$hashedPass = password_hash($pass, PASSWORD_DEFAULT);

					// Register employer with status 'active'
					$query2 = "INSERT INTO employer_info(emp_fname, emp_lname, emp_phone, emp_email, emp_pass, status) 
							   VALUES('$fname','$lname','$phno','$email','$hashedPass','active')";
					$result2 = mysqli_query($conn, $query2);
					
					if($result2){
						$msg = "<script>
									showNotify('Registration successful. Redirecting to Login.',2);
									setTimeout(function(){
										location.replace('employer_login.php');	
									},1000);								
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
		<div class="main">
			<div class="reg-emp-content">
				<h1>Hire interns & freshers <i>faster</i></h1>
				<h2>Post internship for <b>free</b> now</h2>
			</div>
			<div class="reg-emp-background">
				<img src="images/emp-reg-bg.png">

				<!--Employer Register form-->
				<div class="emp-reg-style">
					<form method="POST">
						<div class="form">
							<div class="emp-r-input">
								<div class="r-f-input">
									<label for="first" class="emp-r-label">First Name</label><br>
									<input class="emp-reg-input" id="first" type="text" placeholder="e.g. John" name="fname" required pattern="^[A-Za-z]{2,20}$" value="<?php if(isset($_POST['fname'])){echo $_POST['fname'];}?>">
								</div>
								<div class="emp-r-input">
									<label for="last" class="emp-r-label">Last Name</label><br>
									<input class="emp-reg-input" id="last" type="text" placeholder="e.g. Wick" name="lname" required pattern="^[A-Za-z]{2,20}$" value="<?php if(isset($_POST['lname'])){echo $_POST['lname'];}?>">
								</div>
							</div>
							<div class="emp-r-input">
								<label for="num" class="emp-r-label">Number</label><br>
								<input class="emp-reg-input" id="num" type="text" placeholder="10 digit Mobile number" name="phno" required pattern="^[789][0-9]{9}$" value="<?php if(isset($_POST['phno'])){echo $_POST['phno'];}?>"><br>
							</div>
							<div class="emp-r-input">
								<label for="emp-reg-email" class="emp-r-label">Official Email Id</label><br>
								<input class="emp-reg-input" id="emp-reg-email" type="email" placeholder="employer@companydomain.com" name="email" pattern="^[a-z]{2}(([0-9a-z]+)?\.?([0-9a-z]+)?){0,18}@[A-Za-z]{3,20}\.(com|in|org|edu\.in|gov\.in)$" required value="<?php if(isset($_POST['email'])){echo $_POST['email'];}?>"><br>
							</div>
							<div class="emp-r-input">
								<label for="emp-reg-pass" class="emp-r-label">Password</label><br>
								<input class="emp-reg-input" id="emp-reg-pass" type="password" placeholder="Must be at least 6 characters" name="pass" pattern="^[A-Za-z0-9`~!@#$%^&*]{6,20}$" required><br>
							</div>
							<div class="emp-r-input">
								<label for="emp-reg-cpass" class="emp-r-label">Confirm Password</label><br>
								<input class="emp-reg-input" id="emp-reg-cpass" type="password" placeholder="Must be at least 6 characters" name="cpass" pattern="^[A-Za-z0-9`~!@#$%^&*]{6,20}$" required><br>
							</div>
							<div class="emp-ter-con">
								<p>By registering, you agree to our <a href="#">Terms and Conditions</a>.</p><br>
							</div>
							<div class="emp-reg-sub-btn-hover">
								<input class="emp-reg-sub-btn" type="submit" value="Register" name="signup">
							</div>
							<div class="emp-new-to-internshop">
								<p>Already registered? <a href="employer_login.php">Login</a></p>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="emp-reg-comp">
			<img src="images/emp-reg-comp.png">
		</div>
	</main>

	<footer>
		<?php include("footer.php"); ?>
	</footer>
	<script src="script/script.js"></script>
</body>
</html>
