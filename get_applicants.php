<?php
include("conn.php");

if(isset($_GET['ip_id'])){
    $ip_id = intval($_GET['ip_id']);
    $res = mysqli_query($conn,"
        SELECT s.stu_fname, s.stu_lname, s.stu_email
        FROM applications a
        JOIN student_info s ON a.sid = s.sid
        WHERE a.ip_id = $ip_id
    ");

    if(mysqli_num_rows($res) > 0){
        $output = "";
        while($row=mysqli_fetch_assoc($res)){
            $output .= $row['stu_fname']." ".$row['stu_lname']." (".$row['stu_email'].")\n";
        }
        echo $output;
    } else {
        echo "No applicants yet.";
    }
}
?>
