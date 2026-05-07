<?php
session_start();
include('conn.php');
if(!isset($_SESSION['eid'])){
    header("Location: index.php");
}else{
    $eid = $_SESSION['eid'];
}

if ($_SESSION['status'] == 'unverified') {
    header("Location: company_profile.php");
}

// Fetch categories for dropdown
$categories = [];
$cat_query = "SELECT category_name FROM categories WHERE status='active' AND is_deleted=0 ORDER BY category_name ASC";
$cat_result = mysqli_query($conn, $cat_query);
if($cat_result){
    while($row = mysqli_fetch_assoc($cat_result)){
        $categories[] = $row['category_name'];
    }
}
?>

<html>
<head>
    <title>Internshop | Employer Internship Post</title>
    <link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/employer_internship_form_style.css">
    <script type="text/javascript" src="script/errorMessage.js"></script>
</head>
<body>
    <header>
        <?php include("nav.php"); ?>
    </header>

    <?php
    // Form submission logic
    if (isset($_POST['action']) && ($_POST['action'] == 'Save Draft' || $_POST['action'] == 'Post Internship')) {

        $numRegex = "/^[0-9]+$/";
        $dateRegex = "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/";
        $stringRegex = "/[a-zA-Z\'\",?@\. ]*/";

        foreach ($_POST as $key => $value){
            $$key = mysqli_real_escape_string($conn,$value);
        }

        // Your validation logic here (same as before)...

        if($start_date <= $apply_by){
            $msg = "<script>showNotify('Start date should be later than application date.',1);</script>";
        }else{
            if ($action == "Save Draft") {
                $action = "Drafted";
                $created_on = '';
            }else if($action == "Post Internship"){
                $action = "Posted";
                $created_on = date('Y-m-d');
            }

            $title = $profile." internship at ".$location;

            $query = "INSERT INTO internship_details(title,eid,created_on,action,category,profile,skills,location,openings,start_date,apply_by,duration,role1,role2,role3,role4,role5,stipend,perk1,perk2,perk3,perk4,q1,q2,q3,q4) 
            VALUES('$title',$eid,'$created_on','$action','$category','$profile','$skills','$location',$openings,'$start_date','$apply_by','$duration','$role1','$role2','$role3','$role4','$role5',$stipend,'$perk1','$perk2','$perk3','$perk4','$q1','$q2','$q3','$q4')";

            $result = mysqli_query($conn, $query);

            if ($result) {
                if ($action=='Drafted') {
                    $msg = "<script>showNotify('Internship saved in draft.',3);</script>";                            
                }else if ($action == 'Posted') {
                    $msg = "<script>
                                showNotify('Internship submitted successfully.',2);
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

    <main>
        <div class="emp-internship-container">
            <h1>Post Internship</h1>
            <div class="dash-facing-issue">
                <p>&#9888; Make sure all internship details are correct. As per our policy you won't be able to edit an internship once it is posted. You may save an internship as draft to edit and post later.</p>
            </div>

            <form method="POST" action="">
                <h3>Internship details</h3>
                <div class="internship-form">

                    <!-- Category Dropdown -->
                    <div class="label-input">
                        <label>Category</label><br>
                        <select name="category" class="emp-internship-form-input" required>
                            <option value="">Select Category</option>
                            <?php 
                                foreach($categories as $cat){
                                    $selected = (isset($_POST['category']) && $_POST['category'] == $cat) ? 'selected' : '';
                                    echo "<option value='$cat' $selected>$cat</option>";
                                }
                            ?>  
                        </select>
                    </div>

                    <!-- Other inputs as before -->
                    <div class="label-input">
                        <label>Internship profile</label><br>
                        <input class="emp-internship-form-input" type="text" placeholder="eg. Android App Development" name="profile" required value="<?php if(isset($_POST['profile'])){echo $_POST['profile'];}?>">
                    </div>
                    <div class="label-input">
                        <label>Skills required</label><br>
                        <input class="emp-internship-form-input" type="text" placeholder="eg. Java, Html, Php" name="skills" required value="<?php if(isset($_POST['skills'])){echo $_POST['skills'];}?>">
                    </div>
                    <div class="label-input">
                        <label>Location</label><br>
                        <input class="emp-internship-form-input" type="text" placeholder="eg. Mumbai, Delhi, Remote" name="location" required value="<?php if(isset($_POST['location'])){echo $_POST['location'];}?>">
                    </div>
                    <div class="label-input">
                                <label>Number of openings</label><br>
                                <input class="emp-internship-form-input" type="text" placeholder="eg. 4 (Max 99)" name="openings" required value="<?php if(isset($_POST['openings'])){echo $_POST['openings'];}?>">
                            </div>
                            <div class="label-input">
                                <label>Internship start date</label><br>
                                    <div class="emp-int-radio-btn1">
                                        <input class="emp-internship-form-input" type="date" name="start_date" required value="<?php if(isset($_POST['start_date'])){echo $_POST['start_date'];}?>">
                                    </div>
                            </div>
                            <div class="label-input">
                                <label>Apply by</label><br>
                                    <div class="emp-int-radio-btn1">
                                        <input class="emp-internship-form-input" type="date" name="apply_by" required value="<?php if(isset($_POST['apply_by'])){echo $_POST['apply_by'];}?>">
                                    </div>
                            </div>
                            <div class="label-input">
                                <label>Internship duration</label><br>
                                <input class="emp-internship-form-input" type="text" placeholder="Enter number of months" name="duration" required value="<?php if(isset($_POST['duration'])){echo $_POST['duration'];}?>">
                            </div>
                            <div class="label-input">
                                <label>Intern's day-to-day responsibilities include</label><br>
                                <input class="emp-internship-form-input" type="text" placeholder="1." name="role1" required value="<?php if(isset($_POST['role1'])){echo $_POST['role1'];}?>"><br>
                                <input class="emp-internship-form-input" type="text" placeholder="2." name="role2" value="<?php if(isset($_POST['role2'])){echo $_POST['role2'];}?>"><br>
                                <input class="emp-internship-form-input" type="text" placeholder="3." name="role3" value="<?php if(isset($_POST['role3'])){echo $_POST['role3'];}?>"><br>
                                <input class="emp-internship-form-input" type="text" placeholder="4." name="role4" value="<?php if(isset($_POST['role4'])){echo $_POST['role4'];}?>"><br>
                                <input class="emp-internship-form-input" type="text" placeholder="5." name="role5" value="<?php if(isset($_POST['role5'])){echo $_POST['role5'];}?>"><br>
                            </div>
                        </div>
                        <h3 class="s-p">Stipend & perks</h3>
                        <div class="internship-form">
                            <div>
                                <label>Stipend</label><br>
                                    <div class="label-input">
                                        <label>₹</label>
                                        <input class="stipent-amount" type="text" placeholder="e.g. 100000 (Min Rs. 5000)" name="stipend" required value="<?php if(isset($_POST['stipend'])){echo $_POST['stipend'];}?>">
                                        <label>per month</label><br>
                                        <div class="label-input">
                                            <label>Perks (Optional)</label>
                                            <div class="emp-check-box">
                                                <div class="emp-int-radio-btn1"> 
                                                    <input type="checkbox" name="perk1" value="Certificate of completion." checked><label> Certificate of completion.</label>
                                                </div>
                                                <div class="emp-int-radio-btn2">
                                                    <input type="checkbox" name="perk2" value="Letter of Recommendation." <?php if(isset($_POST['perk2'])){echo "checked";}?>><label> Letter of Recommendation.</label>
                                                </div>
                                                <div class="emp-int-radio-btn1">
                                                    <input type="checkbox" name="perk3" value="Flexible work hours." <?php if(isset($_POST['perk3'])){echo "checked";}?>><label> Flexible work hours.</label>
                                                </div>
                                                <div class="emp-int-radio-btn2">
                                                    <input type="checkbox" name="perk4" value="5 days a week." <?php if(isset($_POST['perk4'])){echo "checked";}?>><label> 5 days a week.</label>
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
                            <input class="emp-internship-form-input" type="text" placeholder="1." name="q2" value="<?php if(isset($_POST['q2'])){echo $_POST['q2'];}?>"><br>
                            <input class="emp-internship-form-input" type="text" placeholder="2." name="q3" value="<?php if(isset($_POST['q3'])){echo $_POST['q3'];}?>"><br>
                            <input class="emp-internship-form-input" type="text" placeholder="3." name="q4" value="<?php if(isset($_POST['q4'])){echo $_POST['q4'];}?>"><br>
                        </div>
                        <div class="post-internship-form1">
                            <input class="post-btn1" type="submit" value="Save Draft" name="action">
                        </div>
                        <div class="post-internship-form2">
                            <input class="post-btn2" type="submit" value="Post Internship" name="action">
                        </div>
                    <!-- Add the rest of your fields as in original code -->

                </div>

    
            </form>
        </div>
    </main>

    <footer>
        <?php include("footer.php") ?>
    </footer>
</body>
</html>
