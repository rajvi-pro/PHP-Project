<?php
session_start();
include("conn.php");

if (!isset($_SESSION['eid'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['status'] == 'unverified') {
    header("Location: company_profile.php");
    exit();
}

if (isset($_GET['rem_ip_id']) && !empty($_GET['rem_ip_id'])) {
    $ip_id = mysqli_real_escape_string($conn, $_GET['rem_ip_id']);
    $queryRem = "UPDATE internship_details SET is_deleted = 1 WHERE ip_id = $ip_id";
    $resultRem = mysqli_query($conn, $queryRem);

    if ($resultRem && mysqli_affected_rows($conn) > 0) {
        echo "<script>
                showNotify('Internship deleted successfully.', 2);
                setTimeout(function(){
                    location.replace('employer_dashboard.php');  
                }, 2000);
              </script>";
    } else {
        echo "<script>
                showNotify('Something went wrong. Please try again.', 1);
              </script>";
    }
}

$eid = mysqli_real_escape_string($conn, $_SESSION['eid']);
$query = "SELECT created_on, ip_id, profile, action FROM internship_details WHERE eid = $eid AND is_deleted = 0 ORDER BY ip_id DESC";
$result = mysqli_query($conn, $query);

function appCount($conn, $ip_id) {
    $query = "SELECT appid FROM applications WHERE ip_id = $ip_id";
    $result = mysqli_query($conn, $query);
    return ($result) ? mysqli_num_rows($result) : 0;
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

<header>
    <?php include("nav.php"); ?>
</header>

<main>
    <div class="emp-dash-container">
        <div class="dashboard-title">
            <p>Employer Dashboard</p>
        </div>  
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            echo '<div class="dashboard-table"><table><tr class="table-head"><th>Posted On</th><th>Profile</th><th>Status</th><th>Applications</th><th colspan="4">Action</th></tr>';
            while ($internship = mysqli_fetch_assoc($result)) {
                $created_on = ($internship['created_on'] === '0000-00-00') ? 'Not Posted' : $internship['created_on'];
                echo "<tr class='table-data'>
                        <td>{$created_on}</td>
                        <td class='p-data'>{$internship['profile']}</td>
                        <td>{$internship['action']}</td>
                        <td>" . appCount($conn, $internship['ip_id']) . "</td>
                        <td><a href='show_applications.php?ip_id={$internship['ip_id']}&status=New' target='_blank'><img src='images/icons/open.png' height='25' width='25' title='Open Applications'></a></td>
                        <td><a href='view_internship.php?ip_id={$internship['ip_id']}&mode=view' target='_blank'><img src='images/icons/view.png' height='35' width='35' title='View Internship'></a></td>";
                if ($internship['action'] === 'Drafted') {
                    echo "<td><a href='edit_internship_form.php?ip_id={$internship['ip_id']}' target='_blank'><img src='images/icons/edit.png' height='35' width='35' title='Edit Internship'></a></td>";
                } else {
                    echo "<td><img src='images/icons/noedit.png' height='35' width='35' title='Cannot edit Internship'></td>";
                }
                echo "<td><a href='employer_dashboard.php?rem_ip_id={$internship['ip_id']}' onclick='return confirmDelete();'><img src='images/icons/trash.png' height='40' width='40' title='Delete Internship'></a></td></tr>";
            }
            echo '</table></div>';
        } else {
            echo "<br><div class='dashboard-title'><p>You haven't posted any internships.</p></div>";
        }
        ?>
    </div>
</main>

<footer>
    <?php include("footer.php"); ?>
</footer>

<script type="text/javascript">
    function confirmDelete() {
        return confirm("Do you really want to delete this internship?");
    }
</script>
</body>
</html>