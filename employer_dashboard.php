<?php
session_start();
include("conn.php");

if (!isset($_SESSION['eid'])) {
    header("Location: index.php");
    exit;
}

if ($_SESSION['status'] == 'unverified') {
    header("Location: company_profile.php");
    exit;
}
?>
<html>
<head>
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

<?php
// 1️⃣ Fetch all active internships (not deleted)
if (isset($_SESSION['eid'])) {
    $eid = mysqli_real_escape_string($conn, $_SESSION['eid']);
    $query = "SELECT created_on, ip_id, profile, action 
              FROM internship_details 
              WHERE eid=$eid AND is_deleted=0 
              ORDER BY ip_id DESC";
    $result = mysqli_query($conn, $query);
}

// 2️⃣ Handle Soft Delete
if (isset($_GET['rem_ip_id']) && !empty($_GET['rem_ip_id'])) {
    $ip_id = mysqli_real_escape_string($conn, $_GET['rem_ip_id']);

    // Instead of DELETE, we just mark as deleted
    $queryRem = "UPDATE internship_details SET is_deleted=1 WHERE ip_id=$ip_id";
    $resultRem = mysqli_query($conn, $queryRem);

    if (mysqli_affected_rows($conn) > 0) {
        $msg = "<script>
                    showNotify('Internship deleted successfully (soft delete).', 2);
                    setTimeout(function(){
                        location.replace('employer_dashboard.php');  
                    }, 2000);
                </script>";
    } else {
        $msg = "<script>showNotify('Something went wrong. Please try again.', 1);</script>";
    }

    echo $msg;
}
?>

<?php
// 3️⃣ Count Applications
function appCount($conn, $ip_id) {
    $num = 0;
    $query = "SELECT appid FROM applications WHERE ip_id=$ip_id";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $num = mysqli_num_rows($result);
    }
    return $num;
}
?>

<!-- main -->
<main>
    <div class="emp-dash-container">
        <div class="dashboard-title">
            <p>Employer Dashboard</p>
        </div>  

        <?php
        if (mysqli_num_rows($result) > 0) {
            echo '
            <div class="dashboard-table">
                <table>
                    <tr class="table-head">
                        <th>Posted On</th>
                        <th>Profile</th>
                        <th>Status</th>
                        <th>Applications</th>
                        <th colspan="4">Action</th>
                    </tr>
            ';
            while ($internship = mysqli_fetch_assoc($result)) {
                if ($internship['created_on'] == '0000-00-00') {
                    $internship['created_on'] = 'Not Posted';
                }

                echo "
                <tr class='table-data'>
                    <td>".$internship['created_on']."</td>
                    <td class='p-data'>".$internship['profile']."</td>
                    <td>".$internship['action']."</td>
                    <td>".appCount($conn, $internship['ip_id'])."</td>
                    <td>
                        <a href='show_applications.php?ip_id=".$internship['ip_id']."&status=New' target='_blank'>
                            <img src='images/icons/open.png' height='25px' width='25px' title='Open Applications'>
                        </a>
                    </td>
                    <td>
                        <a href='view_internship.php?ip_id=".$internship['ip_id']."&mode=view' target='_blank'>
                            <img src='images/icons/view.png' height='35px' width='35px' title='View Internship'>
                        </a>
                    </td>";
                
                if ($internship['action'] == 'Drafted') {
                    echo "
                    <td>
                        <a href='edit_internship_form.php?ip_id=".$internship['ip_id']."' target='_blank'>
                            <img src='images/icons/edit.png' height='35px' width='35px' title='Edit Internship'>
                        </a>
                    </td>";
                } 
                echo "
                    <td>
                        <a href='employer_dashboard.php?rem_ip_id=".$internship['ip_id']."' onclick='return confirmDelete(event)'>
                            <img src='images/icons/trash.png' height='40px' width='40px' title='Delete Internship'>
                        </a>
                    </td>
                </tr>";
            }
            echo "</table></div>";
        } else {
            echo "<br><div class='dashboard-title'><p>You haven't posted any internships.</p></div>";
        }
        ?>
    </div>
</main>

<!-- footer -->
<footer>
    <?php include("footer.php"); ?>
</footer>

<script type="text/javascript">
function confirmDelete(event) {
    if (!confirm("Do you really want to delete this internship?")) {
        event.preventDefault();
        return false;
    }
    return true;
}
</script>

</body>
</html>
