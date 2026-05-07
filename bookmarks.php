<?php
    session_start();
    include('conn.php');

    if (!isset($_SESSION['sid'])) {
        header('Location: index.php');
    }
?>
<html>
<head>
    <title>Internshop | Bookmark</title>
		<link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/bookmarks_style.css">
    <script type="text/javascript" src="script/errorMessage.js"></script>
</head>
<body>
    <!--header-->
    <header>
        <?php include("nav.php");?>
    </header>
    <?php
        if(isset($_GET['rem_ip_id']) && !empty($_GET['rem_ip_id'])){
            $rem_ip_id = mysqli_real_escape_string($conn,$_GET['rem_ip_id']);
            $sid = $_SESSION['sid'];

            
            $query1 = "DELETE FROM bookmarks WHERE sid=$sid AND ip_id= $rem_ip_id";   
            $result1 = mysqli_query($conn,$query1);

            if ($result1) {
                echo "<script>showNotify('Bookmark Deleted',3);
                            setTimeout(function(){
                                location.replace('bookmarks.php'); 
                            },2000);                                
                </script>"; 
            }else{
                echo "<script>showNotify('Failed to delete bookmark.',1);</script>";
            } 
        }
    ?>
    <?php
        $sid = $_SESSION['sid'];
        $bookmarks = array();

        $query = "SELECT * FROM bookmarks WHERE sid=$sid";
        $result = mysqli_query($conn,$query);
        $count = mysqli_num_rows($result);
        if (mysqli_num_rows($result) > 0) {
            while ($in = mysqli_fetch_assoc($result)) {
                $bookmarks[] = $in['ip_id'];        
            }
        }
    ?>
    <main>
        <div class="internship-post-container">
            <div class="dashboard-titels">
                <p>My bookmarks</p>
            </div>
            
            <h2>
                <?php 
                    if ($count<=0) {
                        echo "<div class='dashboard-img'><img src='images/icons/stu_bookmark.png'></div>";
                        echo "No bookmarks found.";
                        echo "<div style='margin-top: 10px'><a href='internships.php'><button class='book-btn'>Browse more internships</button></a></div>";
                    }
                ?>
            </h2>
            <?php
                if (count($bookmarks) > 0){
                    foreach ($bookmarks as $ip_id) {
                        $query = "SELECT ip_id, eid, profile, location, start_date, apply_by, duration, stipend, openings FROM internship_details WHERE ip_id=$ip_id";

                        $result = mysqli_query($conn,$query);

                        if (mysqli_num_rows($result) > 0) {
                            $internship = mysqli_fetch_assoc($result);

                            $eid = $internship['eid'];
                            $q = "SELECT com_dp_path, com_name FROM employer_info WHERE eid=$eid";
                            $res = mysqli_query($conn, $q);

                            if (mysqli_num_rows($res) > 0) {
                                $row = mysqli_fetch_assoc($res);
                                $com_dp_path = $row['com_dp_path'];
                            }

                            echo"
                                <div class='post-container'>
                                    <h3>".$internship['profile']."</h3><br>
                                    <div class='company-img'>
                                        <img class='img-logo' src='".$com_dp_path."' onerror='this.src=\"uploads/com/blank.png\"'>
                                    </div>
                                    <p class='company-name'>".$row['com_name']."</p><br>
                                    <div class='logo-name'>
                                        <img class='loc-logo' src='images/icons/location.png'>
                                        <span class='titles-content'>".$internship['location']."</span>
                                    </div>
                                    <div class='titles'>
                                        <div>Start Date</div>
                                        <div>Duration</div>
                                        <div>Stipend</div>
                                        <div>Openings</div>
                                        <div>Apply by</div>
                                        <div class='view-details'>
                                            <a href='internships_details.php?ip_id=".$internship['ip_id']."' target='_blank'>View Details</a>
                                        </div>
                                    </div>
                                    <div class='titles-content'>
                                        <div>".$internship['start_date']."</div>
                                        <div>".$internship['duration']." months</div>
                                        <div> ₹ ".$internship['stipend']."</div>
                                        <div>".$internship['openings']."</div>
                                        <div>".$internship['apply_by']."</div>
                                        <div class='book'>
                                            <form method='GET'>
                                                <input type='hidden' value='".$internship['ip_id']."' name='rem_ip_id'>
                                                <input type='submit'  class='bookmark' name='remBook' value='Delete &nbsp;Bookmark'>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            ";
                        }
                    }
                }
            ?>
        </div>
    </main>
    <footer>
        <?php include("footer.php");?>
    </footer>
</body>
</html>