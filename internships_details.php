<?php
    session_start();
    include('conn.php');
    $applied = 0;
    if (isset($_SESSION['eid'])){
        header('location: employer_dashboard.php');
    }

    if (isset($_SESSION['sid'])) {
        $ip_id = mysqli_real_escape_string($conn, $_GET['ip_id']);
        $sid = $_SESSION['sid'];
        $applied = 0;

        $q1 = "SELECT appid FROM applications WHERE sid=$sid AND ip_id=$ip_id";
        $r = mysqli_query($conn, $q1);
        if (mysqli_num_rows($r) > 0) {
            $applied = 1;
        }
    }
?>
<html>
    <head>
    <title>Internshop | Internship Details</title>
		<link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/internships_details_style.css">
        <script type="text/javascript" src="script/errorMessage.js"></script>
    </head>
    <body>
        <!--header-->
        <header>
            <?php include("nav.php");?>
        </header>
        <?php
            if (isset($_GET['ip_id'])) {
                $ip_id = mysqli_real_escape_string($conn,$_GET['ip_id']);
    
                $query = "SELECT * FROM internship_details WHERE ip_id=$ip_id";
                $result = mysqli_query($conn,$query);

                if (mysqli_num_rows($result) > 0) {
                	$internship = mysqli_fetch_assoc($result);

                    // Getting DP path from employer_info.
                    $eid = $internship['eid'];
                    $q = "SELECT com_name, com_link, com_about, com_dp_path FROM employer_info WHERE eid=$eid";
                    $res = mysqli_query($conn, $q);

                    if (mysqli_num_rows($res) > 0) {
                        $row = mysqli_fetch_assoc($res);
                        $com_name = $row['com_name'];
                        $com_link = $row['com_link'];
                        $com_about = $row['com_about'];
                        $com_dp_path = $row['com_dp_path'];
                    }
                }
            }
            //echo $msg;
		?>
        <!--main-->
        <main>
            <div class="int-detail-container">
                <div class="comp-caption"><h2><?php echo safePrint($internship['profile'])." internship at ".safePrint($internship['location']); ?></h2></div>
                
                <div class="int-details-containt">
                    <h3><?php echo safePrint($internship['profile']); ?></h3><br>
                    <div class='company-img'>
                        <img class='img-logo' src='<?php echo safePrint($com_dp_path); ?>' onerror='this.src="uploads/com/blank.png"'>
                    </div>
                    <p class='company-name'>
                        <?php 
                            echo safePrint($com_name);
                             
                        ?>
                    </p><br>
                    <div class='logo-name'>
                        <img class='loc-logo' src='images/icons/location.png'>
                        <span class='titles-content'><?php echo safePrint($internship['location']); ?></span>
                    </div>
                    <div class='titles'>
                        <div>Start Date</div>
                        <div>Duration</div>
                        <div>Stipend</div>
                        <div>Apply by</div>
                        <div>Openings</div>
                    </div>
                    <div class='titles-content'>
                        <div><?php echo safePrint($internship['start_date']); ?></div>
                        <div><?php echo safePrint($internship['duration']); ?> months</div>
                        <div><?php echo safePrint($internship['stipend']); ?></div>
                        <div><?php echo safePrint($internship['apply_by']); ?></div>
                        <div><?php echo safePrint($internship['openings']); ?></div>
                    </div>
                    <div class="hr"></div>
                    <div class="int-detail-about-comp">
                        <h3>About Company</h3>
                        <h5><?php 
                            if($com_link){
                                echo "<a href='".safePrint($com_link)."' target='_blank'>Visit website</a>"; 
                            }?>
                        </h5><br>
                        <p>
                            <?php echo safePrint($com_about); ?>
                        </p>
                    </div>
                    <div class="int-detail-about-internship">
                        <h3>About Internship</h3><br>
                        <p>Selected intern's day-to-day responsibilities include:</p><br>
                        <ol>
                            <?php if(!empty($internship['role1'])){ echo "<li>". safePrint($internship['role1']) ."</li>";} ?>
                            <?php if(!empty($internship['role2'])){ echo "<li>". safePrint($internship['role2']) ."</li>";} ?>
                            <?php if(!empty($internship['role3'])){ echo "<li>". safePrint($internship['role3']) ."</li>";} ?>
                            <?php if(!empty($internship['role4'])){ echo "<li>". safePrint($internship['role4']) ."</li>";} ?>
                            <?php if(!empty($internship['role5'])){ echo "<li>". safePrint($internship['role5']) ."</li>";} ?>
                        </ol><br>
                        <h3>Skills Required:</h3><br>
                        <ul>
                            <li><?php echo safePrint($internship['skills']); ?></li>
                        </ul><br>
                        <h3>Perks</h3>
                        <ol>
                            <?php if(!empty($internship['perk1'])){ echo "<li>". safePrint($internship['perk1']) ."</li>";} ?>
                            <?php if(!empty($internship['perk2'])){ echo "<li>". safePrint($internship['perk2']) ."</li>";} ?>
                            <?php if(!empty($internship['perk3'])){ echo "<li>". safePrint($internship['perk3']) ."</li>";} ?>
                            <?php if(!empty($internship['perk4'])){ echo "<li>". safePrint($internship['perk4']) ."</li>";} ?>
                        </ol>
                    </div>
                    <div class="int-detail-sub-btn">
                        <?php
                            if ($applied == 1) {
                                echo"<input type='button' value='Applied' disabled>";
                            }else{
                                echo"
                                <form method='GET' action='student_assessment.php'>
                                    <input type='hidden' value='".safePrint($internship['ip_id'])."' name='ip_id'>
                                    <input type='submit' value='Apply Now' name='apply'>                           
                                </form>
                                ";                                
                            }
                        ?>
                    </div>
                </div>      
            </div>
        </main>
        
        <!--footer-->
        <footer>
            <?php include("footer.php");?>
        </footer>
    </body>
</html>