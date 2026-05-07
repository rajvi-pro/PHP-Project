<?php
session_start();
include("conn.php"); // mysqli connection

// Access control
if (!isset($_SESSION['eid'])) {
    header("Location: index.php");
    exit;
}
if (isset($_SESSION['status']) && $_SESSION['status'] === 'unverified') {
    header("Location: company_profile.php");
    exit;
}

$eid = (int) $_SESSION['eid'];
$msg = '';

// --- Handle soft-delete ---
if (isset($_GET['rem_ip_id']) && $_GET['rem_ip_id'] !== '') {
    $ip_id = (int) $_GET['rem_ip_id'];

    // Only mark is_deleted = 1
    $stmt = $conn->prepare("UPDATE internship_details SET is_deleted = 1 WHERE eid = ? AND ip_id = ? AND is_deleted = 0");
    if ($stmt) {
        $stmt->bind_param("ii", $eid, $ip_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $msg = "<script>
                        showNotify('Internship deleted successfully.', 2);
                        setTimeout(function(){ location.replace('employer_dashboard.php'); }, 2000);
                    </script>";
        } else {
            $msg = "<script>showNotify('Something went wrong or internship already deleted.', 1);</script>";
        }
        $stmt->close();
    } else {
        $msg = "<script>showNotify('Database error. Please try again later.', 1);</script>";
    }
}

// --- Fetch internships (non-deleted only) ---
$query = "SELECT ip_id, title, created_on, action, profile FROM internship_details 
          WHERE eid = ? AND is_deleted = 0 
          ORDER BY ip_id DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $eid);
$stmt->execute();
$result = $stmt->get_result();

// --- Helper: count applications ---
function appCount($conn, $ip_id) {
    $count = 0;
    $stmt = $conn->prepare("SELECT COUNT(*) FROM applications WHERE ip_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $ip_id);
        $stmt->execute();
        $stmt->bind_result($cnt);
        if ($stmt->fetch()) $count = (int)$cnt;
        $stmt->close();
    }
    return $count;
}
?>
