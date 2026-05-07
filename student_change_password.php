<?php 
    session_start();
    include("conn.php");
    
    if(!isset($_SESSION['sid'])){
        header("Location: index.php");
    }else{
        $sid = $_SESSION['sid'];
    }
?>

<!--Student Change Password-->

<html>
    <head>
        <title>Internshop | Update password</title>
		<link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/change_pass_style.css">
        <script type="text/javascript" src="script/errorMessage.js"></script>
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
                            <input class="stu-change-pass-input" type="password" placeholder="Enter old password." name="oldpass" pattern="^[A-Za-z0-9`~!@#$%^&*]{6,20}$" required>
                        </div>
                        <div class="label-input">
                            <label>New password</label><br>
                            <input class="stu-change-pass-input" type="password" placeholder="Enter new password." name="newpass" pattern="^[A-Za-z0-9`~!@#$%^&*]{6,20}$" required>
                        </div>
                        <div class="label-input">
                            <label>Confirm password</label><br>
                            <input class="stu-change-pass-input" type="password" placeholder="Confirm new password." name="repass" pattern="^[A-Za-z0-9`~!@#$%^&*]{6,20}$" required>
                        </div>
                        <div class="label-input">
                            <input class="stu-change-pass-sub-btn" type="submit" value="Update" name="changepass">
                        </div>
                    </div>
                </form>
                <?php
                
                    $passRegex = "/^[A-Za-z0-9`~!@#$%^&*]{6,20}$/";
                    
                    if(isset($_POST['changepass']) && !empty($_POST['changepass'])){
                        
                        foreach($_POST as $key => $value){
                            $$key = mysqli_real_escape_string($conn,$value);
                        }       
                         
                        if(!preg_match($passRegex, $oldpass)) {
                            $msg = "<script>showNotify('Invalid Old password.',1);</script>";
                        }else if(!preg_match($passRegex, $newpass)) {
                            $msg = "<script>showNotify('Invalid new password.',1);</script>";
                        }else if(!preg_match($passRegex, $repass)) {
                            $msg = "<script>showNotify('Invalid confirm password.',1);</script>";
                        }else if($newpass != $repass){
                        	$msg = "<script>showNotify('New password is not same as confirm password.',1);</script>";
                        }else{
                        
                            $query = "UPDATE student_info SET stu_pass='$newpass' WHERE sid= $sid AND stu_pass='$oldpass'";
                            
                            $result = mysqli_query($conn, $query);
                            
                            if (mysqli_affected_rows($conn) > 0) {
                            	$msg = "<script>
			                            showNotify('Password updated successfully.',2);
			                            setTimeout(function(){
			                                location.replace('student_change_password.php');  
			                            },2000);                                
			                        </script>";
                            }else{
  								$msg = "<script>
			                            	showNotify('Failed to update password.',1);                               
			                        	</script>";
 							}
						}

						echo $msg;
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