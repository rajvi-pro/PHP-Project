<?php
session_start();
include("conn.php");

if (!isset($_SESSION['adminid'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['type'], $_GET['id'], $_GET['action'])) {
    $type = $_GET['type']; // 'student' or 'employee'
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    $table = ($type === 'student') ? 'student_info' : 'employer_info';
    $column = ($action === 'Block') ? 1 : 0;

    $query = "UPDATE $table SET is_deleted=$column WHERE " . ($type==='student'?'sid':'eid') . "=$id";
    mysqli_query($conn, $query);
}

header("Location: admin_dashboard.php?section=block");
exit();
?>
