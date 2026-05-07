<?php
session_start();
include("conn.php");
if(!isset($_SESSION['adminid'])){
    header("Location: admin_login.php");
    exit();
}

$eid = $_GET['eid'];
$conn->query("DELETE FROM employees WHERE eid=$eid");
header("Location: admin_employee.php");
exit();
?>
