<?php
session_start();
include("conn.php");

// If user already logged in, redirect
if (isset($_SESSION['sid'])) {
	header('Location: student_dashboard.php');
	exit();
} else if (isset($_SESSION['eid'])) {
	header('Location: employer_dashboard.php');
	exit();
}

if (!isset($_SESSION['reset_email'])) {
	header("Location: forgot_password.php");
	exit();
}

if (isset($_POST['verify_otp'])) {
	$user_otp = $_POST['otp'];
	$session_otp = $_SESSION['reset_otp'];

	if ($user_otp == $session_otp) {
		echo "<script>alert('OTP verified successfully'); window.location='reset_password.php';</script>";
	} else {
		echo "<script>alert('Invalid OTP!');</script>";
	}
}
?>

<html>
<head><title>OTP Verification</title></head>
<body>
	<form method="POST">
		<h2>OTP Verification</h2>
		<label>Enter OTP sent to your email</label>
		<input type="number" name="otp" maxlength="6" required>
		<button type="submit" name="verify_otp">Verify OTP</button>
	</form>
</body>
</html>
