<?php
session_start();
include("conn.php");

if (!isset($_SESSION['adminid'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['ip_id'])) {
    $ip_id = intval($_GET['ip_id']);
    $query = "UPDATE internship_details SET is_deleted=1 WHERE ip_id=$ip_id";
    mysqli_query($conn, $query);
}

header("Location: admin_dashboard.php?section=manage_internships");
exit();
?>
