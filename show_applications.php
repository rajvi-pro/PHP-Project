<?php
    session_start();
    include("conn.php");
    
    
    
    if (!isset($_SESSION['eid'])) {
        header("Location: index.php");
    }

    // Getting title of Internship.
    if (isset($_GET['ip_id']) && !empty($_GET['ip_id'])){
        $ip_id = mysqli_real_escape_string($conn,$_GET['ip_id']);
    
        $query1 = "SELECT title FROM internship_details WHERE ip_id=$ip_id";
        $result1 = mysqli_query($conn,$query1);

        if (mysqli_num_rows($result1)>0) {
            $i = mysqli_fetch_assoc($result1);
            $title = $i['title'];
        }
    }

    // Function to get count of all the application with given status.
    function countApps($conn, $status, $ip_id){
        // Getting count of New applications.
        $query = "SELECT appid FROM applications WHERE ip_id=$ip_id AND status='$status'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            return mysqli_num_rows($result);
        }else{
            return 0;
        }
    }


    // Getting count of all applications.
    $queryT = "SELECT appid FROM applications WHERE ip_id=$ip_id";
    $resultT = mysqli_query($conn, $queryT);

    if (mysqli_num_rows($resultT) > 0) {
        $countTotal = mysqli_num_rows($resultT);
    }else{
        $countTotal = 0;
        $msg = "<script>
                    showNotify('No applications found for this internship.',1);
                    setTimeout(function(){
                        //location.replace('employer_dashboard.php'); 
                        window.close();
                    },2000);                                
                </script>";        
    }

    //echo $countTotal;
?>
<?php
    // Performing actions on particular students.
    
    if (isset($_GET['sid']) && !empty($_GET['sid'])) {
        if (isset($_GET['status']) && !empty($_GET['status'])) {

            $sid = mysqli_real_escape_string($conn,$_GET['sid']);
            $status = mysqli_real_escape_string($conn,$_GET['status']);

            $query1 = "SELECT status FROM applications WHERE sid=$sid AND ip_id=$ip_id";
            $result1 = mysqli_query($conn,$query1);

            if ($result1) {
                $row = mysqli_fetch_assoc($result1);
                $oldStatus = $row['status'];
            }

            //echo $oldStatus;
            if ($status == 'New' && ($oldStatus=='Shortlisted' OR $oldStatus=='Hired' OR $oldStatus=='Rejected')) {
                    $msg = "<script>
                                showNotify('Cannot add student back to ".$status." list.',1);
                                setTimeout(function(){
                                    location.replace(document.referrer); 
                                },2000);                                
                            </script>";  
            }else if (($status == 'Shortlisted' OR $status== 'New') && ($oldStatus=='Hired' OR $oldStatus=='Rejected')) {
                $msg = "<script>
                                showNotify('Cannot add student back to ".$status." list.',1);
                                setTimeout(function(){
                                    location.replace(document.referrer); 
                                },2000);                                
                            </script>"; 
            }else if (($status == 'Shortlisted' OR $status== 'New' OR $status=='Hired') && ($oldStatus=='Rejected')) {
                $msg = "<script>
                                showNotify('Cannot add student back to ".$status." list.',1);
                                setTimeout(function(){
                                    location.replace(document.referrer); 
                                },2000);                                
                            </script>"; 
            }else if (($status == 'Shortlisted' OR $status== 'New' OR $status=='Rejected') && ($oldStatus=='Hired')) {
                $msg = "<script>
                                showNotify('Cannot add student back to ".$status." list.',1);
                                setTimeout(function(){
                                    location.replace(document.referrer); 
                                },2000);                                
                            </script>"; 
            }else{

                $query = "UPDATE applications SET status='$status' WHERE sid=$sid AND ip_id=$ip_id";
                $result = mysqli_query($conn, $query);

                if (mysqli_affected_rows($conn) > 0) {
                    $redir = "show_applications.php?ip_id=".$ip_id."&status=".$status;
                    $msg = "<script>
                                showNotify('Student added to ".$status." list.',3);
                                setTimeout(function(){
                                    location.replace('".$redir."'); 
                                },2000);                                
                            </script>";  
                }else{
                    // Do nothing.
                }
            }
        }
    }
?>
<html>
    <head>
        <title>Internshop | Show Applications</title>
		<link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/show_applications_style.css">
        <script type="text/javascript" src="script/errorMessage.js"></script>
        <script type="text/javascript" src="script/actionAjax.js"></script>
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

            // Getting a list of all the applications for this internship.
            if (isset($_GET['ip_id']) && !empty($_GET['ip_id'])){
                if (isset($_GET['status']) && !empty($_GET['status'])) {

                $eid = mysqli_real_escape_string($conn,$_SESSION['eid']);
                $ip_id = mysqli_real_escape_string($conn,$_GET['ip_id']);
                $status = mysqli_real_escape_string($conn,$_GET['status']);

                $apps = array();

                $query = "SELECT ip_id, eid, sid, applied_on, status FROM applications WHERE eid=$eid AND ip_id=$ip_id AND status='$status' ORDER BY status='New' DESC";
                $appsResult = mysqli_query($conn,$query);
                $count = mysqli_num_rows($appsResult);
                
                if ($count > 0) {
                    while($s = mysqli_fetch_assoc($appsResult)) {
                        // ID of all the students who applied for this internship.
                        // Making a 2-D array for each student's application.

                        $apps[$s['sid']] = $s;  
                    }
                }
                
                }
                echo $msg;
            }
        ?>
        <!--main-->
        <main>
            <div class="application-container">
                <div class="dashboard-title"><p>Applications for <?php if(isset($title)){ echo $title; }?></p></div>
                <div class="filter">
                    <form method="GET">
                        <input type="hidden" name="ip_id" value="<?php if(isset($ip_id)){ echo $ip_id; }?>">
                        <div class="filter-header">
                            <button class="btn1 allbtn" type="submit" value="New" name="status">
                                <span>New <?php echo "(".countApps($conn, 'New', $ip_id).")"; ?></span>
                            </button>

                        </div>
                        
                        <div class="filter-header">
                            <button class="btn2 allbtn" type="submit" value="Shortlisted" name="status">
                                <span>Shortlisted <?php echo "(".countApps($conn, 'Shortlisted', $ip_id).")"; ?></span>
                            </button>
                        </div>
                        
                        <div class="filter-header">
                            <button class="btn3 allbtn" type="submit" value="Hired" name="status">
                                <span>Hired <?php echo "(".countApps($conn, 'Hired', $ip_id).")"; ?></span>
                            </button>
                        </div>
                        
                        <div class="filter-header">
                            <button class="btn4 allbtn" type="submit" value="Rejected" name="status">
                                <span>Rejected <?php echo "(".countApps($conn, 'Rejected', $ip_id).")"; ?></span>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="application-table">
                    <table>
                        <tr class="table-head">
                            <th>Name</th>
                            <th>Applied On</th>
                            <th>Status</th>
                            <th>Resume</th>
                            <th colspan="4">Action</th>
                        <tr>
                            <?php
                                if ($count > 0){
                                    foreach($apps as $key => $value){
                                        $sid = $value['sid'];
                                        $query = "SELECT stu_fname, stu_lname, city, stu_email, stu_phone FROM student_info WHERE sid=$sid";

                                        $result = mysqli_query($conn,$query);
                                        $stud = mysqli_fetch_assoc($result);

                                        echo"
                                            <tr class='table-data'>
                                                <td class='p-data'>".$stud['stu_fname']." ".$stud['stu_lname']."</td>
                                                <td class='p-data'>".$value['applied_on']."</td>
                                                <td>".$value['status']."</td>
                                                <td><a href='review_application.php?ip_id=".$value['ip_id']."&sid=".$sid."' target='_blank'><img class='btnAction' src='images/icons/cv.png' title='Check student resume.'></a>
                                                </td>
                                                <td>
                                                    <a href='show_applications.php?ip_id=".$ip_id."&sid=".$sid."&status=New'>
                                                        <img src='images/icons/new.png' height='35px' width='35px' title='Add back to New list' >
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href='show_applications.php?ip_id=".$ip_id."&sid=".$sid."&status=Shortlisted'>
                                                        <img src='images/icons/shortlisted.png' height='35px' width='35px' title='Shortlist student.' >
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href='show_applications.php?ip_id=".$ip_id."&sid=".$sid."&status=Hired'>
                                                        <img src='images/icons/accepted.png' height='35px' width='35px' title='Hire student.'>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href='show_applications.php?ip_id=".$ip_id."&sid=".$sid."&status=Rejected'>
                                                        <img src='images/icons/rejected.png' height='35px' width='35px' title='Reject student.'>
                                                    </a>
                                                </td>
                                            </tr>
                                        ";
                                    }
                                }
                            ?>
                    </table>
                </div>
            </div>
        </main>
        
        <!--footer-->
        <footer>
            <?php include("footer.php"); ?>
        </footer>
    </body>
</html>

