<?php
session_start();
include("conn.php");

if (!isset($_SESSION['adminid'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $appid = intval($_GET['id']);
    $query = "UPDATE applications SET is_deleted=1 WHERE appid=$appid";
    mysqli_query($conn, $query);
}

header("Location: admin_dashboard.php?section=manage_applications");
exit();
?>
