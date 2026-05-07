<?php
session_start();
include("conn.php");

if (isset($_SESSION['sid'])) {
    $sid = $_SESSION['sid'];
}else{
    $sid = 0;
}

$filters = array_filter($_GET);
$allowedVar = array('location','stipend','profile','keyword','category');

foreach ($filters as $key => $value) {
    if(!in_array($key, $allowedVar)){
        unset($filters[$key]);
    }        
}

foreach ($filters as $key => $value) {
    $filters[$key] = mysqli_real_escape_string($conn, $value);
}

// Make sure category column exists in internship_details table
$query = "SELECT ip_id, eid, profile, location, start_date, apply_by, duration, stipend, openings, category FROM internship_details WHERE"; 

$filCount = count($filters);

if ($filCount) {
    foreach ($filters as $key => $value) {
        switch ($key) {
            case 'location':
                $query .= " location LIKE '%$value%'";
                break;
            case 'stipend':
                $query .= " stipend >= $value";
                break;
            case 'profile':
                $query .= " profile LIKE '%$value%'";
                break;
            case 'keyword':
                $query .= " profile LIKE '%$value%' OR location LIKE '%$value%' OR stipend LIKE '%$value%'";
                break;
            case 'category':
                $category_value = urldecode($value);
                $category_value = mysqli_real_escape_string($conn, $category_value);
                $query .= " category='$category_value'";
                break;
        }
        $filCount--;
        if ($filCount > 0) {
            $query .= " AND";
        }
    }
    $query .= " AND action='posted' ORDER BY ip_id DESC";
}else{
    $query .= " action='posted' ORDER BY ip_id DESC";
}

$result = mysqli_query($conn,$query);
$count = $result ? mysqli_num_rows($result) : 0;
?>

<html>
<head>
    <title>Internshop | Internship</title>
    <link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/internships_style.css">
    <script type="text/javascript" src="script/errorMessage.js"></script>
</head>
<body>
<header>
    <?php 
        include("nav.php");
        $queryBook = "SELECT ip_id FROM bookmarks WHERE sid=$sid";
        $resultBook = mysqli_query($conn,$queryBook);
        $bookmarked = array();
        if (mysqli_num_rows($resultBook)>0) {
            while ($booked = mysqli_fetch_assoc($resultBook)) {
                foreach ($booked as $key => $value) {
                    $bookmarked[] = $value;     
                }
            }
        }
    ?>
</header>

<!--Bookmark handling -->
<?php
if(isset($_POST['ip_id']) && !empty($_POST['ip_id'])){
    $ip_id = mysqli_real_escape_string($conn,$_POST['ip_id']);
    if ($sid) {
        $query1 = "SELECT * FROM bookmarks WHERE sid=$sid AND ip_id=$ip_id";
        $result1 = mysqli_query($conn,$query1);
        if (mysqli_num_rows($result1)>0) {
            echo "<script>
                    showNotify('Already bookmarked.',1);
                    setTimeout(function(){ location.replace('internships.php'); },2000);                                
                  </script>";
        } else {
            $query2 = "INSERT INTO bookmarks(sid,ip_id) VALUES($sid, $ip_id)";
            $result2 = mysqli_query($conn,$query2);
            echo $result2 ? "<script>showNotify('Bookmark added.',2);setTimeout(function(){location.replace('internships.php');},2000);</script>" : "<script>showNotify('Failed to add bookmark.',1);</script>";
        }
    } else {
        echo "<script>
                showNotify('User login required to bookmark.',1);
                setTimeout(function(){ location.replace('internships.php'); },2000); 
              </script>";
    }
}
?>

<!--Main content-->
<main>
    <div class="internship-post-container">
        <h2><?php echo $count>0 ? "$count internships found." : "No internship found."; ?></h2>

        <form method="GET">
            <div class="filter-container">
                <h3>Filters</h3>
                <div>
                    <label>Location</label><br>
                    <input class="filter-input" type="text" name="location" placeholder="e.g.Mumbai, Remote" value="<?php echo $filters['location'] ?? ''; ?>">
                </div>
                <div class="label-input">
                    <label>Minimum monthly stipend (₹)</label><br>
                    <input class="filter-input" type="text" name="stipend" placeholder="e.g. ₹1000" value="<?php echo $filters['stipend'] ?? ''; ?>">
                </div>
                <div class="label-input">
                    <label>Profile</label><br>
                    <input class="filter-input" type="text" name="profile" placeholder="e.g Web Development" value="<?php echo $filters['profile'] ?? ''; ?>">
                </div>
                <br>
                <h3> OR </h3>
                <div class="label-input">
                    <label>Keyword</label><br>
                    <input class="filter-input" type="text" name="keyword" placeholder="Search with keyword." value="<?php echo $filters['keyword'] ?? ''; ?>">
                </div>
                <div class="label-input">
                    <input class="filter-sub-btn" type="submit" value="Search">
                </div>
            </div>
        </form>

        <?php
        if ($count>0){
            while($internship = mysqli_fetch_assoc($result)){
                $eid = $internship['eid'];
                $q = "SELECT com_dp_path, com_name FROM employer_info WHERE eid=$eid";
                $res = mysqli_query($conn, $q);
                $row = mysqli_fetch_assoc($res);
                $com_dp_path = $row['com_dp_path'] ?? 'uploads/com/blank.png';

                echo "
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
                        <a href='internships_details.php?ip_id=".$internship['ip_id']."' target='_blank'>
                            <div class='view-details'>View Details</div>
                        </a>                                        
                    </div>
                    <div class='titles-content'>
                        <div>".$internship['start_date']."</div>
                        <div>".$internship['duration']." months</div>
                        <div> ₹ ".$internship['stipend']."</div>
                        <div>".$internship['openings']."</div>
                        <div>".$internship['apply_by']."</div>
                ";

                if (in_array($internship['ip_id'], $bookmarked)) {
                    echo "<div><input type='submit' class='bookmarked' value='Bookmark Added'></div></div></div>";
                } else {
                    echo "<div>
                            <form method='POST'>
                                <input type='hidden' value='".$internship['ip_id']."' name='ip_id'>
                                <input type='submit' class='bookmark' value='Add to bookmarks'>
                            </form>
                          </div></div></div>";
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
