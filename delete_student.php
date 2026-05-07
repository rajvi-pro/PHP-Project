<?php
session_start();
include("conn.php");
if(!isset($_SESSION['adminid'])){
    header("Location: admin_login.php");
    exit();
}

$sid = $_GET['sid'];
$conn->query("DELETE FROM students WHERE sid=$sid");
header("Location: admin_students.php");
exit();
?>
