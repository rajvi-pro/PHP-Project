<?php
    session_start();
    include('conn.php');

    $msg = '';

    if (!isset($_SESSION['sid'])){
        header('Location: student_login.php');

    }else if (!isset($_GET['ip_id']) OR empty($_GET['ip_id'])) {
        header('Location: internships.php');

    }

?>
<html>
    <head>
        <title>Internshop | Student Assessment</title>
		<link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/student_assessment_style.css">
        <script type="text/javascript" src="script/errorMessage.js"></script>
    </head>
    <body>
        <!--header-->
        <header>
            <?php 
                include("nav.php"); 
            ?>
        </header>
        <?php
            if (isset($_GET['ip_id'])) {
                $ip_id = mysqli_real_escape_string($conn, $_GET['ip_id']);
                
                $sid = $_SESSION['sid'];
                
                $qr1 = "SELECT appid FROM applications WHERE sid=$sid AND ip_id=$ip_id";
                $r = mysqli_query($conn, $qr1);
                if (mysqli_num_rows($r) > 0) {
                    $msg = "<script>
                                showNotify('Already applied to this internship',1);
                                setTimeout(function(){
                                    location.replace('internships.php');  
                                },3000);                                
                            </script>"; 
                }else{
                    $query = "SELECT eid, profile, location,q1, q2, q3, q4 FROM internship_details WHERE ip_id=$ip_id";
                    $result = mysqli_query($conn, $query);
                    $num = mysqli_num_rows($result);

                    if ($num>0) {
                        $internship = mysqli_fetch_assoc($result);
                    }
                }
                echo $msg;
            }
        ?>
        <?php
            if (isset($_POST['submit']) && !empty($_POST['submit'])){
                
                $stringRegex = "/[a-zA-Z\'\",?@\. ]*/";
				$numRegex = "/[0-9]{1,3}/";

                $q1=$q2=$q3=$q4='';

                foreach($_POST as $key => $value){
                    $$key = mysqli_real_escape_string($conn,$value);
                }
                
                $applied_on = date('Y-m-d');
                
                // Apply validations for Assessment questions here.
				if(!preg_match($numRegex, $eid)){
					$msg = "<script>showNotify('Something went wrong.',1);</script>";
				  }else if (!preg_match($stringRegex, $q1) || (strlen($q1) > 250) || (strlen($q1) < 0)) {
					$msg = "<script>showNotify('Invalid characters or length.',1);</script>";
				  }else if (!preg_match($stringRegex, $q2) || (strlen($q2) > 250) || (strlen($q1) < 0)) {
					$msg = "<script>showNotify('Invalid characters or length.',1);</script>";
				  }else if (!preg_match($stringRegex, $q3) || (strlen($q3) > 250) || (strlen($q1) < 0)) {
					$msg = "<script>showNotify('Invalid characters or length.',1);</script>";
				  }else if (!preg_match($stringRegex, $q4) || (strlen($q4) > 250) || (strlen($q1) < 0)){
					$msg = "<script>showNotify('Invalid characters or length.',1);</script>";
				  }else{
				
                    $query2 = "INSERT INTO applications(ip_id,eid,sid,applied_on,q1,q2,q3,q4) VALUES('$ip_id','$eid','$sid','$applied_on','$q1','$q2','$q3','$q4')";
                    $result2 = mysqli_query($conn, $query2);

                    if ($result2){
                        $msg = "<script>showNotify('Application successful. Apply to more internships.',2);
                                    setTimeout(function(){
                                        location.replace('internships.php');  
                                    },3000);                                
                        </script>";
                    }else{
                        $msg = "<script>showNotify('Something went wrong. Please try again.',1);</script>";
                    }
                }
            }
            echo $msg;
        ?>
        <!--main-->
        <main>
            <div class="stu-assess-container">
                <div class="comp-name">
                    <h2><?php if(isset($internship['profile']) && isset($internship['location'])){echo $internship['profile']." internship at ".$internship['location'];} ?></h2>
                </div>
                <div class="cover-letter-container">
                    <h3 class="title">Assessment Questions</h3>
                    
                    <div class="cover-letter-tips">
                        <p class="cover-letter-tips-content">See tips to answer this question</p>
                        <div class="tips-content">
                            <ul>
                                <li>
                                    &#x000BB; <b>Highlight your strengths -</b> mention any experience (internship/job experience), accomplishments,
                                    skills that are relevant to the role.
                                </li>
                                <li>
                                    &#x000BB; <b>Show enthusiasm -</b> mention what excites you about this role and the company. 
                                    You can research about company.
                                </li>
                                <li>
                                    &#x000BB; <b>Do not copy answers</b> from the ChatGPT.
                                </li>
                                <li>
                                    <h2>&#x000BB; <b><a href="student_resume.php" target="_blank">Update your resume</a></b> before applying.</h2>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <form method="POST">
                    <input type="hidden" name="eid" value="<?php if(isset($internship['eid'])){echo $internship['eid'];} ?>">
                <div class="assessment-container">
                    <?php
                        if (isset($internship)) {
                            $internship = array_slice($internship,3);
                            foreach ($internship as $key => $value) {
                                if (!empty($value)) {
                                    echo "<div class='assess-content'>";
                                    echo "<p class='assess-question'>".strtoupper($key).". ".$value."</p>";
                                    echo "<div class='assess-textarea'>
                                            <textarea name='".$key."' style='resize: none;' placeholder='Answer is mandatory for selection. Maximum limit is 250 characters. ' required>";
                                            if(isset($_POST[$key])){ echo $_POST[$key];}
                                    echo"</textarea></div></div>";                                
                                }
                            }
                        }   
                    ?>
                </div>
                
                <div class="stu-assess-sub-btn">
                    <input type="submit" value="Submit" name="submit">
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