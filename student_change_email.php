<?php 
    session_start();
    include("conn.php");
    
    if(!isset($_SESSION['sid'])){
        header("Location: index.php");
    }else{
        $sid = $_SESSION['sid'];
        $query= "SELECT stu_email FROM student_info WHERE sid=$sid";
            
        $result = mysqli_query($conn,$query);
        
        if(mysqli_num_rows($result)>0){
            $row = mysqli_fetch_assoc($result);
            $oldemail = $row['stu_email'];
        }
    }
?>

<!--Student Change Email-->

<html>
    <head>
        <title>Internshop | Update Email</title>
		<link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/change_email_style.css">
        <script type="text/javascript" src="script/errorMessage.js"></script>
    </head>
    <body>
        <!--header-->
        <header>
            <?php include("nav.php"); ?>
        </header>
        <?php
            $emailRegex = "/^[a-z]{2}(([0-9a-z]+)?\.?([0-9a-z]+)?){0,18}@gmail\.com$/";
            $passRegex = "/^[A-Za-z0-9`~!@#$%^&*]{6,20}$/";
            
            if(isset($_POST['changeemail']) && !empty($_POST['changeemail'])){
                
                foreach($_POST as $key => $value){
		            $$key = mysqli_real_escape_string($conn,$value);
		        }

                if (!preg_match($emailRegex, $newemail) || !preg_match($passRegex, $pass)){
                    echo "<script>showNotify('Invalid email ID or password.',1);</script>";
                }else{
    				$query = "SELECT * FROM student_info WHERE stu_email='$newemail'";
    				$result = mysqli_query($conn, $query);

    				if(mysqli_num_rows($result)>0){
                        echo"<script>showNotify('Email already exists.',1);</script>";
                    }else{
              			
              			// Updating student email ID.
                        $query = "UPDATE student_info SET stu_email='$newemail' WHERE stu_pass='$pass' AND stu_email='$oldemail' AND sid=$sid";
                        
                        $result = mysqli_query($conn, $query);
                        $rows = mysqli_affected_rows($conn);
                                
                        if($rows > 0){
                            echo "<script>showNotify('Email updated successfully.',2);
    	                        setTimeout(function(){
    	                            location.replace('student_change_email.php'); 
    	                        },2000);                                
                			</script>"; 
                        }else{
                            echo "<script>showNotify('Invalid email ID or password.',1);</script>"; 
                        }
                    }
                }
                echo $msg;
            }
        ?>
        <!--Change Email Form-->
        <main>
            <div class="stu-change-email-container">
                <h2>Change Email</h2>
                <form method="POST">
                    <div class="stu-change-email">
                        <div class="label-input">
                            <p>
                                Note: Please note that all the data associated with your account <b><?php if(isset($oldemail)){echo $oldemail;} ?></b> will be 
                                linked to your new email address after this change. Also, ensure that you have access to 
                                both the email accounts for making the change.
                            </p>
                        </div>
                        <div class="label-input">
                            <label>New Email</label><br>
                            <input class="stu-change-email-input" type="email" placeholder="yash@gmail.com" name="newemail" value="<?php if(isset($_POST['newemail'])){echo $_POST['newemail'];}?>" pattern="^[a-z]{2}(([0-9a-z]+)?\.?([0-9a-z]+)?){0,18}@gmail\.com$" required>
                        </div>
                        <div class="label-input">
                            <label>Confirm Password</label><br>
                            <input class="stu-change-email-input" type="password" placeholder="Confirm password to update email." name="pass" value="<?php if(isset($_POST['pass'])){echo $_POST['pass'];}?>" pattern="^[A-Za-z0-9`~!@#$%^&*]{6,20}$" required>
                        </div>
                        <div class="label-input">
                            <input class="stu-change-email-sub-btn" type="submit" value="Change email" name="changeemail">
                        </div>
                    </div>
                </form>
                
            </div>
        </main>
        
        <!--footer-->
        <footer>
            <?php include("footer.php") ?>
        </footer>
    </body>
</html>