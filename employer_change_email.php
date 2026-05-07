<?php 
    session_start();
    include("conn.php");
    
    if(!isset($_SESSION['eid'])){
        header("Location: index.php");
    }

    if ($_SESSION['status'] == 'unverified') {
        header("Location: company_profile.php");
    }

    $eid = $_SESSION['eid'];
    $query= "SELECT emp_email FROM employer_info WHERE eid=$eid";
        
    $result = mysqli_query($conn,$query);
    
    if(mysqli_num_rows($result)>0){
        $row = mysqli_fetch_assoc($result);
        $oldemail = $row['emp_email'];
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
            $emailRegex = "/^[a-z]{2}(([0-9a-z]+)?\.?([0-9a-z]+)?){0,18}@[A-Za-z]{3,20}\.(com|in|org|edu\.in|gov\.in)$/";
            $passRegex = "/^[A-Za-z0-9`~!@#$%^&*]{6,20}$/";
            
            if(isset($_POST['changeemail']) && !empty($_POST['changeemail'])){
                
                foreach($_POST as $key => $value){
                    $$key = mysqli_real_escape_string($conn,$value);
                }
                if (!preg_match($emailRegex, $newemail) || !preg_match($passRegex, $pass)){
                    echo "<script>showNotify('Invalid email ID or password.',1);</script>";
                }else{
                    $query = "SELECT * FROM employer_info WHERE emp_email='$newemail'";
                    $result = mysqli_query($conn, $query);

                    if(mysqli_num_rows($result)>0){
                        echo"<script>showNotify('Email already exists.',1);</script>";
                    }else{
                        
                        // Updating student email ID.
                        $query = "UPDATE employer_info SET emp_email='$newemail' WHERE emp_pass='$pass' AND emp_email='$oldemail' AND eid=$eid";
                        
                        $result = mysqli_query($conn, $query);
                        $rows = mysqli_affected_rows($conn);
                                
                        if($rows > 0){
                            echo "<script>showNotify('Email updated successfully.',2);
                                setTimeout(function(){
                                    location.replace('student_change_email.php'); 
                                },2000);                                
                            </script>"; 
                        }else{
                            echo "<script>showNotify('Invalid email ID or passwordaaaa.',1);</script>"; 
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
                            <input class="stu-change-email-input" type="email" placeholder="yash@gmail.com" name="newemail" value="<?php if(isset($_POST['newemail'])){echo $_POST['newemail'];}?>" pattern="^[a-z]{2}(([0-9a-z]+)?\.?([0-9a-z]+)?){0,18}@[A-Za-z]{3,20}\.(com|in|org|edu\.in|gov\.in)$" required>
                        </div>
                        <div class="label-input">
                            <label>Confirm Password</label><br>
                            <input class="stu-change-email-input" type="password" placeholder="Confirm password to update email." name="pass" value="<?php if(isset($_POST['newemail'])){echo $_POST['pass'];}?>" pattern="^[A-Za-z0-9`~!@#$%^&*]{6,20}$" required>
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