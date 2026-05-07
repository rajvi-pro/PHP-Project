<?php 
    session_start();
    include("conn.php");
    
    if(!isset($_SESSION['sid'])){
        header("Location: index.php");
    }

    if (isset($_SESSION['sid'])) {
        $sid = mysqli_real_escape_string($conn, $_SESSION['sid']);

        $query = "SELECT sid, stu_fname, stu_lname, resume_path FROM student_info WHERE sid=$sid";
        $result = mysqli_query($conn, $query);
        if(mysqli_num_rows($result)>0){
           $stud = mysqli_fetch_assoc($result);

            $studName = strtolower($sid.$stud['stu_fname'].$stud['stu_lname']);
            $studResumePath = $stud['resume_path'];
        }
    }
?>

<!--Student Resume-->

<html>
    <head>
        <title>Internshop | Update Resume</title>
		<link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/student_resume_style.css">
        <script type="text/javascript" src="script/errorMessage.js"></script>
    </head>
    <body>
        <!--header-->
        <header>
            <?php include("nav.php"); ?>
        </header>
        <?php
            //var_dump($_POST);
            if (isset($_POST['upload']) && !empty($_POST['upload'])) {
                if (isset($_FILES['resume_file']) && !empty($_FILES['resume_file'])){
                    
                    // Setting file upload variables.
                    $targetDir = "uploads/resume/";
                    $baseFileName = basename($_FILES['resume_file']['name']);
                    $fileType = strtolower(pathinfo($baseFileName,PATHINFO_EXTENSION));
                    $fileSize = $_FILES['resume_file']['size'];

                    // Filename should be customized for every student.
                    $fileName = $studName.time();
                    $targetFilePath = $targetDir.$fileName.'.'.$fileType;
                    
                    if ($fileSize > 1153433){
                        $msg = "<script>showNotify('File size should be less than 1 MB.',1);</script>";
                    }else if($fileType != 'pdf'){
                        $msg = "<script>showNotify('Upload resume only in PDF format.',1);</script>";
                    }else{
                        $query = "UPDATE student_info SET resume_path='$targetFilePath' WHERE sid=$sid";
                        if (mysqli_query($conn, $query)) {
                            if(move_uploaded_file($_FILES['resume_file']['tmp_name'], $targetFilePath)){
                                unlink($studResumePath);    // Deleting previous resume file.
                                $msg = "<script>
                                    showNotify('Your resume has been uploaded.',2);
                                    setTimeout(function(){
                                        location.replace('student_resume.php');  
                                    },2000);                                
                                </script>";
                            }
                        }else{
                            $msg = "<script>showNotify('Something went wrong. Please try again.',1);</script>";
                        } 
                    }
                }else{
                    $msg = "<script>showNotify('Invalid file format or size.',1);</script>";
                }
                echo $msg;
            }                
         ?>
        <!--main-->
        <main>
            <div class="resume-container">
                <h2>Resume</h2>
                <div class="profile-message1">
                    <p> 
                        &#9888; Whenever you apply to an internship, 
                        this is the profile that the employer will see. Always make sure it is up to date. Resume file format should be PDF with size less than 1 MB. Check a sample resume <a href="" target="_blank">here.</a>
                    </p>
                </div>
                <form method="POST" enctype="multipart/form-data" action="">
                    <div class="btn1">
                        <input class="choose-file-btn" type="file" name="resume_file"><br><br>
                        <input class="resume-upload-btn" type="submit" value="Upload Resume" name="upload"><br><br>
                    </div>
                    <div class="resume-div">
                        <?php
                            if ($studResumePath) {
                                echo "<object class='resume-file' data='$studResumePath' width='100%' height='150%'></object>";                                
                            }else{
                                echo "You haven't upload any resume.";
                            }
                        ?>
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