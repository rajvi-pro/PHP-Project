<?php
session_start();
include("conn.php");

// Redirect if student not logged in
if (!isset($_SESSION['sid'])) {
    header('Location: index.php');
    exit();
}

$sid = $_SESSION['sid'];
?>

<html>
<head>
    <title>Internshop | Student Dashboard</title>
    <link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/student_dashboard_style.css">
    <script type="text/javascript" src="script/errorMessage.js"></script>
</head>
<body>

<!-- Header -->
<header>
    <?php include("nav.php"); ?>
</header>

<?php
// =============================
// 1️⃣ SOFT DELETE APPLICATION
// =============================
if (isset($_GET['rem_app_ip_id']) && !empty($_GET['rem_app_ip_id'])) {
    $rem_app_ip_id = mysqli_real_escape_string($conn, $_GET['rem_app_ip_id']);

    // Update instead of delete
    $query1 = "UPDATE applications 
               SET is_deleted = 1 
               WHERE sid = $sid AND ip_id = $rem_app_ip_id";

    $result1 = mysqli_query($conn, $query1);

    if ($result1 && mysqli_affected_rows($conn) > 0) {
        $msg = "<script>
                    showNotify('Your application was moved to Recycle Bin.', 3);
                    setTimeout(function() {
                        location.replace('student_dashboard.php');
                    }, 2000);
                </script>";
    } else {
        $msg = "<script>showNotify('Failed to delete application.', 1);</script>";
    }

    echo $msg;
}
?>

<?php
// =============================
// 2️⃣ FETCH ACTIVE APPLICATIONS
// =============================

$query = "SELECT a.*, 
                 id.profile, id.location, id.eid,
                 e.com_name
          FROM applications a
          INNER JOIN internship_details id ON a.ip_id = id.ip_id
          INNER JOIN employer_info e ON e.eid = id.eid
          WHERE a.sid = $sid AND a.is_deleted = 0
          ORDER BY a.applied_on DESC";

$result = mysqli_query($conn, $query);
$count = mysqli_num_rows($result);
?>

<!-- Main Content -->
<main>
    <div class="dash-container">
        <div class="dash-content">
            <p>My Applications</p>
        </div>

        <?php if ($count > 0): ?>
            <div class="dashboard-table">
                <table>
                    <tr class="table-head">
                        <th>Applied On</th>
                        <th>Profile</th>
                        <th>Company</th>
                        <th>Status</th>
                        <th class="th-msg">Message</th>
                        <th colspan="2">Action</th>
                    </tr>

                    <?php while ($internship = mysqli_fetch_assoc($result)): 
                        // Determine status message
                        $status = $internship['status'];
                        $message = '';

                        switch ($status) {
                            case 'New':
                                $status = "Applied";
                                $message = "Your application is under process.";
                                break;
                            case 'Shortlisted':
                                $message = "Your application has been shortlisted. The employer will contact you soon.";
                                break;
                            case 'Hired':
                                $message = "🎉 Congratulations! You've been hired for this internship.";
                                break;
                            case 'Rejected':
                                $status = "Not Selected";
                                $message = "Unfortunately, you were not selected. Try applying to more internships!";
                                break;
                            default:
                                $message = "Status not available.";
                                break;
                        }
                    ?>

                    <tr class="table-data">
                        <td class="p-data"><?= htmlspecialchars($internship['applied_on']) ?></td>
                        <td class="p-data"><?= htmlspecialchars($internship['profile']) ?></td>
                        <td class="p-data"><?= htmlspecialchars($internship['com_name']) ?></td>
                        <td class="p-data"><?= htmlspecialchars($status) ?></td>
                        <td class="p-data"><?= htmlspecialchars($message) ?></td>
                        <td>
                            <a href="review_application.php?ip_id=<?= $internship['ip_id'] ?>&sid=<?= $sid ?>" target="_blank">
                                <img class="btnAction" src="images/icons/cv.png" title="View Application">
                            </a>
                        </td>
                        <td>
                            <a href="student_dashboard.php?rem_app_ip_id=<?= $internship['ip_id'] ?>" onclick="return confirmDelete();">
                                <img src="images/icons/trash.png" height="40px" width="40px" title="Delete Application">
                            </a>
                        </td>
                    </tr>

                    <?php endwhile; ?>

                </table>
            </div>
        <?php else: ?>
            <div class="no-data">
                <img class="err-img" src="images/dash_img1.png" alt="No Applications">
                
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- Footer -->
<footer>
    <?php include("footer.php"); ?>
</footer>

<!-- JS Confirm Delete -->
<script type="text/javascript">
function confirmDelete() {
    return confirm("Are you sure you want to move this application to Recycle Bin?");
}
</script>

</body>
</html>
