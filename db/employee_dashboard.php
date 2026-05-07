<?php
session_start();
include("conn.php"); // must create $conn = new mysqli(...);

// ---------------- Access Control ----------------
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


// ---------------- Handle Soft Delete ----------------
if (isset($_GET['rem_ip_id']) && $_GET['rem_ip_id'] !== '') {
    $ip_id = (int) $_GET['rem_ip_id'];

    $stmt = $conn->prepare("
        UPDATE internship_details 
        SET is_deleted = 1 
        WHERE eid = ? AND ip_id = ? AND is_deleted = 0
    ");
    if ($stmt) {
        $stmt->bind_param("ii", $eid, $ip_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $msg = "<script>showNotify('Internship deleted successfully.',2); 
                    setTimeout(function(){ location.replace('employer_dashboard.php'); },2000);</script>";
        } else {
            $msg = "<script>showNotify('Something went wrong or internship already deleted.',1);</script>";
        }
        $stmt->close();
    } else {
        $msg = "<script>showNotify('Database error. Please try again later.',1);</script>";
    }
}


// ---------------- Fetch Internships ----------------
$result = false;

$stmt = $conn->prepare("
    SELECT created_on, ip_id, profile, action 
    FROM internship_details 
    WHERE eid = ? AND is_deleted = 0 
    ORDER BY ip_id DESC
");

if ($stmt) {
    $stmt->bind_param("i", $eid);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    die("<p style='color:red;'>Query Error: " . $conn->error . "</p>");
}


// ---------------- Helper Function ----------------
function appCount($conn, $ip_id) {
    $count = 0;
    $ip_id = (int) $ip_id;
    $stmt = $conn->prepare("SELECT COUNT(*) FROM applications WHERE ip_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $ip_id);
        $stmt->execute();
        $stmt->bind_result($cnt);
        if ($stmt->fetch()) $count = (int) $cnt;
        $stmt->close();
    }
    return $count;
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Internshop | Employer Dashboard</title>
    <link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/employer_dashboard_style.css">
    <script type="text/javascript" src="script/errorMessage.js"></script>
</head>
<body>

    <!-- header -->
    <header>
        <?php include("nav.php"); ?>
    </header>

    <!-- Notifications -->
    <?php if ($msg !== '') echo $msg; ?>

    <!-- Main -->
    <main>
        <div class="emp-dash-container">
            <div class="dashboard-title">
                <p>Employer Dashboard</p>
            </div>

            <?php if ($result && $result->num_rows > 0): ?>
                <div class="dashboard-table">
                    <table>
                        <tr class="table-head">
                            <th>Posted On</th>
                            <th>Profile</th>
                            <th>Status</th>
                            <th>Applications</th>
                            <th colspan="4">Action</th>
                        </tr>

                        <?php while ($internship = $result->fetch_assoc()): 
                            $created_on = ($internship['created_on'] === '0000-00-00') 
                                ? 'Not Posted' 
                                : $internship['created_on'];
                            $ip_id = (int) $internship['ip_id'];
                            $profile_safe = htmlspecialchars($internship['profile'], ENT_QUOTES);
                            $action_safe = htmlspecialchars($internship['action'], ENT_QUOTES);
                        ?>
                        <tr class="table-data">
                            <td><?php echo $created_on; ?></td>
                            <td class="p-data"><?php echo $profile_safe; ?></td>
                            <td><?php echo $action_safe; ?></td>
                            <td><?php echo appCount($conn, $ip_id); ?></td>

                            <td>
                                <a href="show_applications.php?ip_id=<?php echo $ip_id; ?>&status=New" target="_blank">
                                    <img src="images/icons/open.png" height="25" width="25" title="Open Applications" alt="Open">
                                </a>
                            </td>
                            <td>
                                <a href="view_internship.php?ip_id=<?php echo $ip_id; ?>&mode=view" target="_blank">
                                    <img src="images/icons/view.png" height="35" width="35" title="View Internship" alt="View">
                                </a>
                            </td>
                            <td>
                                <?php if ($internship['action'] === 'Drafted'): ?>
                                    <a href="edit_internship_form.php?ip_id=<?php echo $ip_id; ?>" target="_blank">
                                        <img src="images/icons/edit.png" height="35" width="35" title="Edit Internship" alt="Edit">
                                    </a>
                                <?php else: ?>
                                    <img src="images/icons/noedit.png" height="35" width="35" title="Cannot edit Internship" alt="No Edit">
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="employer_dashboard.php?rem_ip_id=<?php echo $ip_id; ?>" onclick="return confirmDelete(event)">
                                    <img src="images/icons/trash.png" height="40" width="40" title="Delete Internship" alt="Delete">
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                </div>
            <?php else: ?>
                <br>
                <div class="dashboard-title"><p>You haven't posted any internships.</p></div>
            <?php endif; ?>

            <?php if ($result && is_object($result)) $result->free(); ?>
        </div>
    </main>

    <!-- footer -->
    <footer>
        <?php include("footer.php"); ?>
    </footer>

    <script type="text/javascript">
        function confirmDelete(event) {
            var response = confirm("Do you really want to delete this internship?");
            if (!response) {
                event.preventDefault();
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
