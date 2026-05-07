<?php
    session_start();
    include('conn.php');

    if(!isset($_SESSION['eid'])){
        header("Location: index.php");
    }

    if ($_SESSION['status'] == 'unverified') {
        header("Location: company_profile.php");
    }

    $eid = $_SESSION['eid'];

    if (isset($_GET['ip_id']) && !empty($_GET['ip_id'])) {

        $ip_id = mysqli_real_escape_string($conn,$_GET['ip_id']);

      //  $q1 = "SELECT * FROM internship_details WHERE ip_id=$ip_id AND eid=$eid";
      $sql = "UPDATE internship_details SET is_deleted = 1 WHERE ip_id = '$ip_id' AND eid = '$eid'";
      if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Internship deleted successfully'); window.location='employer_dashboard.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

        $r1 = mysqli_query($conn, $q1);
        
        if (mysqli_num_rows($r1)>0) {
            $arr = mysqli_fetch_assoc($r1);
            foreach ($arr as $key => $value) {
                $$key = mysqli_real_escape_string($conn,$value);
                $_POST[$key] = mysqli_real_escape_string($conn,$value);
                
                if (isset($_POST['action']) && $_POST['action'] == 'Posted') {
                    $msg = "<script>
                                showNotify('Cannot edit already posted internship. Post a new internship.',1);
                                setTimeout(function(){
                                    location.replace('employer_internship_form.php');  
                                },2000);                                
                        </script>";
                }
            }
        }else{
            $msg = "<script>showNotify('Internship not found.',1);</script>";
        }
    }

    
    //var_dump($_POST);
?>

<!--Employer Internship Form-->
<html>
    <head>
        <title>Internshop | Edit Internship</title>
		<link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/employer_internship_form_style.css">
        <script type="text/javascript" src="script/errorMessage.js"></script>
    </head>
    <body>
    
        <!--header-->
        <header>
            <?php 
                include("nav.php"); 
                echo $msg;
            ?>
        </header>
        <?php
            if (isset($_POST['btnAction']) && !empty($_POST['btnAction'])) {

                $numRegex = "/^[0-9]+$/";
                $dateRegex = "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/";
                $stringRegex = "/[a-zA-Z\'\",?@\. ]*/";

                // Creating variables with respective names.
                foreach ($_POST as $key => $value){
                    $$key = mysqli_real_escape_string($conn,$value);

                }

                // All validations will come here.

                if(isset($category) && !preg_match($stringRegex, $category) || strlen($category)>50) {
                    $msg = "<script>showNotify('Invalid category.',1);</script>";
                }else if(isset($profile) && !preg_match($stringRegex, $profile) || strlen($profile)>100) {
                    $msg = "<script>showNotify('Invalid profile.',1);</script>";
                }else if(isset($skills) && !preg_match($stringRegex, $skills) || strlen($skills)>100) {
                    $msg = "<script>showNotify('Invalid skills',1);</script>";
                }else if(isset($location) && !preg_match($stringRegex, $location) || strlen($location)>50) {
                    $msg = "<script>showNotify('Invalid location',1);</script>";
                }else if(isset($openings) && !preg_match($numRegex, $openings) || intval($openings)>100) {
                    $msg = "<script>showNotify('Invalid openings.',1);</script>";
                }else if(isset($start_date) && !preg_match($dateRegex, $start_date) || strlen($start_date)>50) {
                    $msg = "<script>showNotify('Invalid start date.',1);</script>";
                }else if(isset($apply_by) && !preg_match($dateRegex, $apply_by) || strlen($apply_by)>50) {
                    $msg = "<script>showNotify('Invalid apply by date.',1);</script>";
                }else if(isset($duration) && !preg_match($numRegex, $duration) || intval($duration)>12) {
                    $msg = "<script>showNotify('Invalid duration.',1);</script>";
                }else if(isset($role1) && !preg_match($stringRegex, $role1) || strlen($role1)>200 || strlen($role1)<0) {
                    $msg = "<script>showNotify('Invalid role1',1);</script>";
                }else if(isset($role2) && !preg_match($stringRegex, $role2) || strlen($role2)>200) {
                    $msg = "<script>showNotify('Invalid role2',1);</script>";
                }else if(isset($role3) && !preg_match($stringRegex, $role3) || strlen($role3)>200) {
                    $msg = "<script>showNotify('Invalid role3',1);</script>";
                }else if(isset($role4) && !preg_match($stringRegex, $role4) || strlen($role4)>200) {
                    $msg = "<script>showNotify('Invalid role4',1);</script>";
                }else if(isset($role5) && !preg_match($stringRegex, $role5) || strlen($role5)>200) {
                    $msg = "<script>showNotify('Invalid role5',1);</script>";
                }else if(isset($stipend) && !preg_match($numRegex, $stipend) || intval($stipend)>100000) {
                    $msg = "<script>showNotify('Invalid stipend.',1);</script>";
                }else if(isset($perk1) && !preg_match($stringRegex, $perk1) || strlen($perk1)>30 || strlen($perk1)<0) {
                    $msg = "<script>showNotify('Invalid perk1',1);</script>";
                }else if(isset($perk2) && !preg_match($stringRegex, $perk2) || strlen($perk2)>30) {
                    $msg = "<script>showNotify('Invalid perk2',1);</script>";
                }else if(isset($perk3) && !preg_match($stringRegex, $perk3) || strlen($perk3)>30) {
                    $msg = "<script>showNotify('Invalid perk3',1);</script>";
                }else if(isset($perk4) && !preg_match($stringRegex, $perk4) || strlen($perk4)>30) {
                    $msg = "<script>showNotify('Invalid perk4',1);</script>";
                }else if(isset($q1) && !preg_match($stringRegex, $q1) || strlen($q1)>250 || strlen($q1)<0) {
                    $msg = "<script>showNotify('Invalid q1',1);</script>";
                }else if(isset($q2) && !preg_match($stringRegex, $q2) || strlen($q2)>250) {
                    $msg = "<script>showNotify('Invalid q2',1);</script>";
                }else if(isset($q3) && !preg_match($stringRegex, $q3) || strlen($q3)>250) {
                    $msg = "<script>showNotify('Invalid q3',1);</script>";
                }else if(isset($q4) && !preg_match($stringRegex, $q4) || strlen($q4)>250) {
                    $msg = "<script>showNotify('Invalid q4',1);</script>";
                }else if($start_date <= $apply_by){
                    $msg = "<script>showNotify('Start date should be later than application date.',1);</script>";
                }else{

                    // Creating undefined variables.
                    if ($btnAction == "Save Draft") {
                        $action = "Drafted";
                        $created_on = '';
                    }else if($btnAction == "Post Internship"){
                        $action = "Posted";
                        $created_on = date('Y-m-d');
                    }

                    $title = $profile." internship at ".$location;

                    // Query to insert all data.
                    $query = "UPDATE internship_details SET title='$title',eid='$eid',created_on='$created_on',action='$action',category='$category',profile='$profile',skills='$skills',location='$location',openings='$openings',start_date='$start_date',apply_by='$apply_by',duration='$duration',role1='$role1',role2='$role2',role3='$role3',role4='$role4',role5='$role5',stipend='$stipend',perk1='$perk1',perk2='$perk2',perk3='$perk3',perk4='$perk4',q1='$q1',q2='$q2',q3='$q3',q4='$q4' WHERE ip_id='$ip_id'";

                    $result = mysqli_query($conn, $query);

                    if ($result) {
                        if ($action == 'Drafted') {
                            $msg = "<script> 
                                    showNotify('Internship saved as drafted.',3);                                  
                                </script>";                            
                        }else if ($action == 'Posted') {
                            $msg = "<script>showNotify('Internship submitted successfully.',2);
                                    setTimeout(function(){
                                        location.replace('employer_dashboard.php');  
                                    },2000);                                
                            </script>";
                        }
                    }else{
                        $msg = "<script>showNotify('Failed to submit internship.',1);</script>";
                    }
                }
                echo $msg;
            }

        ?>
        <!--main-->
        <main>
            <div class="emp-internship-container">
                <h1>Edit Internship</h1>
                    <form method="POST" action="">
                        <input type="hidden" name="ip_id" value="<?php echo $_POST['ip_id'];?>">
                        <h3>Internship details</h3>
                        <div class="internship-form">
                        	<div class="label-input">
                                <label>Category</label><br>
                                <select name="category" class="emp-internship-form-input" value="Hello">
                                    <?php echo "<option value='$category'>$category</option>"; ?>
                                    <option value="Information Technology">Information Technology</option>
                                    <option value="Web Development">Web Development</option>
                                    <option value="Cyber Security">Cyber Security</option>
                                    <option value="Human Resource">Human Resource</option>
								</select>
                            </div>
                            <div class="label-input">
                                <label>Internship profile</label><br>
                                <input class="emp-internship-form-input" type="text" placeholder="eg. Android App Development" name="profile" required value="<?php echo $profile;?>">
                            </div>
                            <div class="label-input">
                                <label>Skills requird</label><br>
                                <input class="emp-internship-form-input" type="text" placeholder="eg. Java, Html, Php"name="skills" required value="<?php echo $skills;?>">
                            </div>
                            <div class="label-input">
                                <label>Location</label><br>
                                <input class="emp-internship-form-input" type="text" placeholder="eg. Mumbai, Delhi, Remote" name="location" required value="<?php echo $location;?>">
                            </div>
                            <div class="label-input">
                                <label>Number of openings</label><br>
                                <input class="emp-internship-form-input" type="text" placeholder="eg. 4 (Max 99)" name="openings" required value="<?php echo $openings;?>">
                            </div>
                            <div class="label-input">
                                <label>Internship start date</label><br>
                                    <div class="emp-int-radio-btn1">
                                        <input class="emp-internship-form-input" type="date" name="start_date" required value="<?php echo $start_date;?>">
                                    </div>
                            </div>
                            <div class="label-input">
                                <label>Apply by</label><br>
                                    <div class="emp-int-radio-btn1">
                                        <input class="emp-internship-form-input" type="date" name="apply_by" required value="<?php echo $apply_by;?>">
                                    </div>
                            </div>
                            <div class="label-input">
                                <label>Internship duration</label><br>
                                <input class="emp-internship-form-input" type="text" placeholder="Enter number of months" name="duration" required value="<?php echo $duration;?>">
                            </div>
                            <div class="label-input">
                                <label>Intern's day-to-day responsibilities include</label><br>
                                <input class="emp-internship-form-input" type="text" placeholder="1." name="role1" required value="<?php echo $role1;?>"><br>
                                <input class="emp-internship-form-input" type="text" placeholder="2." name="role2" value="<?php echo $role2;?>"><br>
                                <input class="emp-internship-form-input" type="text" placeholder="3." name="role3" value="<?php echo $role3;?>"><br>
                                <input class="emp-internship-form-input" type="text" placeholder="4." name="role4" value="<?php echo $role4;?>"><br>
                                <input class="emp-internship-form-input" type="text" placeholder="5." name="role5" value="<?php echo $role5;?>"><br>
                            </div>
                        </div>
                        <h3 class="s-p">Stipend & perks</h3>
                        <div class="internship-form">
                            <div>
                                <label>Stipend</label><br>
                                    <div class="label-input">
                                        <label>₹</label>
                                        <input class="stipent-amount" type="text" placeholder="e.g. 100000 (Min Rs. 5000)" name="stipend" required value="<?php echo $stipend;?>">
                                        <label>per month</label><br>
                                        <div class="label-input">
                                            <label>Perks (Optional)</label>
                                            <div class="emp-check-box">
                                                <div class="emp-int-radio-btn1"> 
                                                    <input type="checkbox" name="perk1" value="Certificate of completion." checked><label> Certificate of completion.</label>
                                                </div>
                                                <div class="emp-int-radio-btn2">
                                                    <input type="checkbox" name="perk2" value="Letter of Recommendation." <?php if(isset($perk2)){echo "checked";}?>><label> Letter of Recommendation.</label>
                                                </div>
                                                <div class="emp-int-radio-btn1">
                                                    <input type="checkbox" name="perk3" value="Flexible work hours." <?php if(isset($perk3)){echo "checked";}?>><label> Flexible work hours.</label>
                                                </div>
                                                <div class="emp-int-radio-btn2">
                                                    <input type="checkbox" name="perk4" value="5 days a week." <?php if(isset($perk4)){echo "checked";}?>><label> 5 days a week.</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <h3 class="s-p">Cover letter, availability & assignment question</h3>
                        <div class="internship-form">
                            <div class="cover-letter-text">
                                <p class="cover-letter-p">
                                    Cover letter to be asked to applicant by default. If you wish,
                                    you may ask upto three assessment questions. 
                                </p><br>
                            </div>
                            <input class="emp-internship-form-input" type="text" placeholder="1." name="q1"  value="Why should be you hired for this role?" readonly required><br>
                            <input class="emp-internship-form-input" type="text" placeholder="1." name="q2" value="<?php echo $q2;?>"><br>
                            <input class="emp-internship-form-input" type="text" placeholder="2." name="q3" value="<?php echo $q3;?>"><br>
                            <input class="emp-internship-form-input" type="text" placeholder="3." name="q4" value="<?php echo $q4;?>"><br>
                        </div>
                        <?php
                            if (isset($_GET['mode']) && $_GET['mode']=='view') {
                                echo '<div class="post-internship-form2">
                                        <input class="post-btn2" type="submit" value="Edit Internship" name="btnAction">
                                    </div>';
                            }else{
                                echo'
                                    <div class="post-internship-form1">
                                        <input class="post-btn1" type="submit" value="Save Draft" name="btnAction">
                                    </div>
                                    <div class="post-internship-form2">
                                        <input class="post-btn2" type="submit" value="Post Internship" name="btnAction">
                                    </div>
                                ';
                            }
                        ?>
                        
                    </form>
            </div>
        </main>
        
        <!--footer-->
        <footer>
            <?php include("footer.php") ?>
        </footer>
    </body>
</html>