<?php 
	session_start();
	include("conn.php");
	
	if(!isset($_SESSION['adminId'])){
		header("Location: index.php");
	}

 /*   if ($_SESSION['status'] == 'unverified') {
        header("Location: company_profile.php");
    }	*/

	$adminId = $_SESSION['adminId'];
	$query= "SELECT a_password FROM admin WHERE adminId=$adminId";
	$result = mysqli_query($conn,$query);
	
	if(mysqli_num_rows($result)>0){
		$row = mysqli_fetch_assoc($result);
		$oldemail = $row['a_password'];
	}

?>

<!--Student Change Password-->

<html>
	<head>
		<title>Internshop | Update Password</title>
		<link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" type="text/css" href="css/change_pass_style.css">
	</head>
	<body>
		<!--header-->
		<header>
			<?php include("nav.php"); ?>
		</header>
		
		<!--Change Password Form-->
		<main>
			<div class="stu-change-pass-container">
				<h2>Change password</h2>
				<form method="POST">
					<div class="stu-change-pass">
						<div>
							<label>Old password</label><br>
							<input class="stu-change-pass-input" type="password" placeholder="Must be atleast 6 characters" name="oldpass">
						</div>
						<div class="label-input">
							<label>New password</label><br>
							<input class="stu-change-pass-input" type="password" placeholder="Must be atleast 6 characters" name="newpass">
						</div>
						<div class="label-input">
							<label>Confirm password</label><br>
							<input class="stu-change-pass-input" type="password" placeholder="Must be atleast 6 characters" name="repass">
						</div>
						<div class="label-input">
							<input class="stu-change-pass-sub-btn" type="submit" value="Update" name="changepass">
						</div>
					</div>
				</form>
				<?php
				
					$passRegex = "/^[A-Za-z0-9`~!@#$%^&*]{6,20}$/";
					$flag = 0;
					
					if(isset($_POST['changepass']) && !empty($_POST['changepass'])){
						if(isset($_POST['oldpass']) && preg_match($passRegex, $_POST['oldpass'])){
							if(isset($_POST['newpass']) && preg_match($passRegex, $_POST['newpass'])){
								if(isset($_POST['repass']) && preg_match($passRegex, $_POST['repass'])){
									
									$oldpass = mysqli_real_escape_string($conn, $_POST['oldpass']);
									$newpass = mysqli_real_escape_string($conn, $_POST['newpass']);
									$repass = mysqli_real_escape_string($conn, $_POST['repass']);
									
									$fleg = 1;
									
									$query = "UPDATE adminn SET a_password='$newpass' WHERE adminId= $adminId AND a_password='$oldpass'";
									
									$result = mysqli_query($conn, $query);
									
									$temp = mysqli_affected_rows($conn);
									
									
									if($temp>0){
										echo"
										<script>
											alert('Password updated succussfully.');
										</script>
										";
									}else{
										echo"
											<script>
												alert('Failed to update password.');
											</script>
										";
									}
									
									
								}else{
									echo"
										<script>
											alert('Invalid Retype Password.');
										</script>
									";
								}
							}else{
								echo"
									<script>
										alert('Invalid New Password.');
									</script>
								";
							}
							
						}else{
							echo"
								<script>
									alert(' Invalid Old Password');
								</script>
							";
						}
						
					}
				?>
			</div>
		</main>
		
		<!--footer-->
		<footer>
			<?php include("footer.php") ?>
		</footer>
	</body>
</html>