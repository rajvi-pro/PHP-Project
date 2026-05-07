<?php
    session_start();
    include("conn.php");

    if (isset($_SESSION['eid'])) {
        $eid = $_SESSION['eid'];
    }else if(isset($_SESSION['sid'])){
    	$sid = $_SESSION['sid'];
    }else{
    	header("Location: index.php");
    }

?>
<html>
    <head>
        <title>Internshop | Review Application</title>
		<link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/review_application_style.css">
        <script type="text/javascript" src="script/errorMessage.js"></script>
    </head>
    <body>
    
        <!--header-->
        <header>
            <?php include("nav.php"); ?>
        </header>
        <?php
			if (isset($_GET['ip_id']) && !empty($_GET['ip_id'])) {
				if (isset($_GET['sid']) && !empty($_GET['sid'])) {

					$ip_id = mysqli_real_escape_string($conn,$_GET['ip_id']);
	    			$sid = mysqli_real_escape_string($conn,$_GET['sid']);

	                $queryAnswer = "SELECT q1, q2, q3, q4, status FROM applications WHERE ip_id=$ip_id AND sid=$sid ";
	                $resultAnswer = mysqli_query($conn,$queryAnswer);

	                if (mysqli_num_rows($resultAnswer)>0) {
	                	$answer = mysqli_fetch_assoc($resultAnswer);
	                	//var_dump($answer);
	                	
	                	$queryStud = "SELECT stu_fname, stu_lname, gender, city, stu_email, stu_phone, resume_path, dp_path FROM student_info WHERE sid=$sid";
	                	$resultStud = mysqli_query($conn,$queryStud);

	                	if (mysqli_num_rows($resultStud)>0){
	                		$student = mysqli_fetch_assoc($resultStud);
	                		//var_dump($stud);

							$queryQues = "SELECT q1, q2, q3, q4 FROM internship_details WHERE ip_id=$ip_id";
							$resultQues = mysqli_query($conn,$queryQues);

							if (mysqli_num_rows($resultQues)>0){
								$ques = mysqli_fetch_assoc($resultQues);
								//var_dump($ques);
							}else{
								$msg = "<script>
										showNotify('Questions not found.',1);
										setTimeout(function(){
											location.replace('employer_dashboard.php'); 
										},2000);                                
									</script>";
							}
	                	}else{
		                	$msg = "<script>
									showNotify('Student not found.',1);
		                            setTimeout(function(){
		                                location.replace('employer_dashboard.php'); 
		                            },2000);                                
		                        </script>";
		                }

	                }else{
	                	$msg = "<script>
								showNotify('No applications found for this internship.',1);
	                            setTimeout(function(){
	                                location.replace('employer_dashboard.php'); 
	                            },2000);                                
	                        </script>";
	                }
				}else{
                	$msg = "<script>
							showNotify('Student not found.',1);
                            setTimeout(function(){
                                location.replace('employer_dashboard.php'); 
                            },2000);                                
                        </script>";
                }
			}

			echo $msg;
		?>
        <!--main-->
        <main>
            <div class="review-container">
                <div class="review-title"><p>Student Application</p></div>
                
                <div class="stud-resume-container">
                    <div class="resume-title"><p>Resume</p></div>
                    <div><object class="resume-file" data="<?php echo $student['resume_path']; ?>" width="100%" height="150%"></object></div>
                </div>
                
                <div class="stud-container">
                	<div class="stud-profile-container">
	                    <div class="resume-title"><p>Profile</p></div>
	                    <div class="stud-info">
		                    <p><?php echo $student['stu_fname']." ".$student['stu_lname']; ?></p>
		                    <p><?php echo $student['gender']; ?></p>
		                    <p><?php echo $student['stu_email']; ?></p>
		                    <p><?php echo $student['stu_phone']; ?></p>
		                    <p><?php echo $student['city']; ?></p>	
	                    </div>
	                    <div>
	                    	<img class="stud-img" onerror='setDefault(this)' src="<?php echo $student['dp_path']; ?>">
	                    </div>
		            </div>
	                
	                <div class="stud-answer-container">
	                    <div class="resume-title"><p>Answers</p></div>
	                    <div class="ques-content">
	                    	<div class="ans-content">
	                    		<p class="ques"><?php echo $ques['q1']; ?></p>
	                    		<p class="ans"><?php echo safePrint($answer['q1']); ?></p>
	                    	</div>
	                    	<div class="ans-content">
	                    		<p class="ques"><?php echo $ques['q2']; ?></p>
	                    		<p class="ans"><?php echo safePrint($answer['q2']); ?></p>
	                    	</div>

	                    	<div class="ans-content">
	                    		<p class="ques"><?php echo $ques['q3']; ?></p>
		                    	<p class="ans"><?php echo $answer['q3']; ?></p>
	                    	</div>
	                    	<div class="ans-content">
	                    		<p class="ques"><?php echo $ques['q4']; ?></p>
		                    	<p class="ans"><?php echo $answer['q4']; ?></p>
	                    	</div>                    	
	                    </div>
	                </div>
                </div>
            </div>
        </main>
        
        <!--footer-->
        <footer>
            <?php include("footer.php"); ?>
        </footer>
    </body>
</html>