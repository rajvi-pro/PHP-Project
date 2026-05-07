<?php 
session_start();
include("conn.php");

if(!isset($_SESSION['sid'])){
    header("Location: index.php");
    exit();
}else{
    $sid = $_SESSION['sid'];
    $query= "SELECT stu_email, stu_fname, stu_lname FROM student_info WHERE sid=$sid";
    $result = mysqli_query($conn,$query);

    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        $email = $row['stu_email'];
        $name = $row['stu_fname']." ".$row['stu_lname'];
    }
}

if(isset($_POST['delete']) && !empty($_POST['delete'])){

    // Sanitize POST variables
    foreach($_POST as $key => $value){
        $$key = mysqli_real_escape_string($conn, $value);
    }

    // Soft delete: mark is_deleted=1
    $query1 = "UPDATE student_info 
               SET is_deleted = 1 
               WHERE stu_email='$email' AND stu_pass='$pass' AND is_deleted = 0";

    $result1 = mysqli_query($conn, $query1);

    if(mysqli_affected_rows($conn) > 0){
        session_destroy();
        $msg = "<script>
                showNotify('Account deleted successfully.',3);
                setTimeout(function(){
                    location.replace('index.php');  
                },2000);                                 
            </script>";
    }else{
        $msg = "<script>
                showNotify('Invalid password or account already deleted.',1);                               
            </script>";
    }
    echo $msg;
}
?>

<!--Student Delete Account-->

<html>
    <head>
        <title>Internshop | Student Delete Account</title>
        <link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/delete_account_style.css">
        <script type="text/javascript" src="script/errorMessage.js"></script>
    </head>
    <body>
        <!--header-->
        <header>
            <?php include("nav.php"); ?>
        </header>
        
        <!--main-->
        <main>
            <div class="delete-account-container">
                <h2>Delete my account</h2>
                <form method="POST">
                    <div class="delete-account-text">
                        <p class="messege">
                            Hello <?php if(!empty($name)){ echo htmlspecialchars($name); } ?>, we’re sorry to see you go.
                        </p>
                        <p class="messege">
                            Please note that deleting your account is irreversible and all the data associated with your
                            <b><?php if(isset($email)){echo htmlspecialchars($email);} ?></b> account (including access to trainings) will be permanently deleted.      
                        </p>
                    </div>
                    <div class="delete-account">
                        <div class="label-input">
                            <p class="messege-bold">
                                Before you leave, please tell us why you'd like to delete your Internshop account.
                                This information will help us improve.
                            </p>
                            <textarea placeholder="Your feedback matters. Max 500 characters." class="delete-account-textarea" name="feedback" style="resize: none;"><?php if(isset($_POST['feedback'])){echo htmlspecialchars($_POST['feedback']);} ?></textarea>
                        </div>
                        <div class="label-input">
                            <label>Password</label><br>
                            <input class="delete-account-input" type="password" placeholder="Confirm password to delete account." name="pass" required>
                        </div>
                        <div class="label-input">
                            <input class="delete-account-sub-btn" type="submit" value="Bye! Bye! Internshop" name="delete">
                        </div>
                    </div>
                </form>
            </div>
        </main>
        
        <!--footer-->
        <footer>
            <?php include("footer.php"); ?>
        </footer>
    </body>
</html>
