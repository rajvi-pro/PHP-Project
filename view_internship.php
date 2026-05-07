<?php
session_start();
include('conn.php');

if (!isset($_SESSION['eid'])) {
    header("Location: index.php");
    exit();
}

$eid = mysqli_real_escape_string($conn, $_SESSION['eid']);
$msg = '';

if (isset($_GET['ip_id']) && !empty($_GET['ip_id'])) {
    $ip_id = mysqli_real_escape_string($conn, $_GET['ip_id']);

    $query = "SELECT * FROM internship_details WHERE ip_id = $ip_id AND eid = $eid AND is_deleted = 0";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        foreach ($data as $key => $value) {
            $$key = $value; // no need to escape again, already safe from DB
        }
    } else {
        $msg = "<script>showNotify('Something went wrong or internship not found.',1);</script>";
    }
} else {
    $msg = "<script>showNotify('Something went wrong. Please try again.',1);</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Internshop | Internship</title>
    <link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/employer_internship_form_style.css">
    <script type="text/javascript" src="script/errorMessage.js"></script>
</head>
<body>

    <!--header-->
    <header>
        <?php include("nav.php"); ?>
    </header>

    <?php echo $msg; ?>

    <!--main-->
    <main>
        <div class="emp-internship-container">
            <h1>View Internship</h1>
            <form method="POST" action="">
                <h3>Internship details</h3>
                <div class="internship-form">
                    <div class="label-input">
                        <label>Category</label><br>
                        <select name="category" class="emp-internship-form-input" disabled>
                            <option value="Information Technology" <?php if (isset($category) && $category === 'Information Technology') echo 'selected'; ?>>Information Technology</option>
                            <option value="Web Development" <?php if (isset($category) && $category === 'Web Development') echo 'selected'; ?>>Web Development</option>
                            <option value="Cyber Security" <?php if (isset($category) && $category === 'Cyber Security') echo 'selected'; ?>>Cyber Security</option>
                            <option value="Human Resource" <?php if (isset($category) && $category === 'Human Resource') echo 'selected'; ?>>Human Resource</option>
                        </select>
                    </div>
                    <div class="label-input">
                        <label>Internship profile</label><br>
                        <input class="emp-internship-form-input" type="text" name="profile" value="<?php echo isset($profile) ? htmlspecialchars($profile) : ''; ?>" disabled>
                    </div>
                    <div class="label-input">
                        <label>Skills required</label><br>
                        <input class="emp-internship-form-input" type="text" name="skills" value="<?php echo isset($skills) ? htmlspecialchars($skills) : ''; ?>" disabled>
                    </div>
                    <div class="label-input">
                        <label>Location</label><br>
                        <input class="emp-internship-form-input" type="text" name="location" value="<?php echo isset($location) ? htmlspecialchars($location) : ''; ?>" disabled>
                    </div>
                    <div class="label-input">
                        <label>Number of openings</label><br>
                        <input class="emp-internship-form-input" type="text" name="openings" value="<?php echo isset($openings) ? htmlspecialchars($openings) : ''; ?>" disabled>
                    </div>
                    <div class="label-input">
                        <label>Internship start date</label><br>
                        <input class="emp-internship-form-input" type="date" name="start_date" value="<?php echo isset($start_date) ? htmlspecialchars($start_date) : ''; ?>" disabled>
                    </div>
                    <div class="label-input">
                        <label>Apply by</label><br>
                        <input class="emp-internship-form-input" type="date" name="apply_by" value="<?php echo isset($apply_by) ? htmlspecialchars($apply_by) : ''; ?>" disabled>
                    </div>
                    <div class="label-input">
                        <label>Internship duration</label><br>
                        <input class="emp-internship-form-input" type="text" name="duration" value="<?php echo isset($duration) ? htmlspecialchars($duration) : ''; ?>" disabled>
                    </div>
                    <div class="label-input">
                        <label>Intern's day-to-day responsibilities include</label><br>
                        <input class="emp-internship-form-input" type="text" name="role1" value="<?php echo isset($role1) ? htmlspecialchars($role1) : ''; ?>" disabled><br>
                        <input class="emp-internship-form-input" type="text" name="role2" value="<?php echo isset($role2) ? htmlspecialchars($role2) : ''; ?>" disabled><br>
                        <input class="emp-internship-form-input" type="text" name="role3" value="<?php echo isset($role3) ? htmlspecialchars($role3) : ''; ?>" disabled><br>
                        <input class="emp-internship-form-input" type="text" name="role4" value="<?php echo isset($role4) ? htmlspecialchars($role4) : ''; ?>" disabled><br>
                        <input class="emp-internship-form-input" type="text" name="role5" value="<?php echo isset($role5) ? htmlspecialchars($role5) : ''; ?>" disabled><br>
                    </div>
                </div>

                <h3 class="s-p">Stipend & perks</h3>
                <div class="internship-form">
                    <div>
                        <label>Stipend</label><br>
                        <div class="label-input">
                            <label>₹</label>
                            <input class="stipent-amount" type="text" name="stipend" value="<?php echo isset($stipend) ? htmlspecialchars($stipend) : ''; ?>" disabled>
                            <label>per month</label><br>
                            <div class="label-input">
                                <label>Perks (Optional)</label>
                                <div class="emp-check-box">
                                    <div class="emp-int-radio-btn1">
                                        <input type="checkbox" value="Certificate of completion." <?php if (isset($perk1) && $perk1 === "Certificate of completion.") echo "checked"; ?> disabled>
                                        <label> Certificate of completion.</label>
                                    </div>
                                    <div class="emp-int-radio-btn2">
                                        <input type="checkbox" value="Letter of Recommendation." <?php if (isset($perk2) && $perk2 === "Letter of Recommendation.") echo "checked"; ?> disabled>
                                        <label> Letter of Recommendation.</label>
                                    </div>
                                    <div class="emp-int-radio-btn1">
                                        <input type="checkbox" value="Flexible work hours." <?php if (isset($perk3) && $perk3 === "Flexible work hours.") echo "checked"; ?> disabled>
                                        <label> Flexible work hours.</label>
                                    </div>
                                    <div class="emp-int-radio-btn2">
                                        <input type="checkbox" value="5 days a week." <?php if (isset($perk4) && $perk4 === "5 days a week.") echo "checked"; ?> disabled>
                                        <label> 5 days a week.</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="s-p">Cover letter, availability & assignment question</h3>
                <div class="internship-form">
                    <div class="cover-letter-text">
                        <p class="cover-letter-p">
                            Cover letter to be asked to applicant by default. If you wish,
                            you may ask up to three assessment questions.
                        </p><br>
                    </div>
                    <input class="emp-internship-form-input" type="text" name="q1" value="Why should be you hired for this role?" readonly disabled><br>
                    <input class="emp-internship-form-input" type="text" name="q2" value="<?php echo isset($q2) ? htmlspecialchars($q2) : ''; ?>" disabled><br>
                    <input class="emp-internship-form-input" type="text" name="q3" value="<?php echo isset($q3) ? htmlspecialchars($q3) : ''; ?>" disabled><br>
                    <input class="emp-internship-form-input" type="text" name="q4" value="<?php echo isset($q4) ? htmlspecialchars($q4) : ''; ?>" disabled><br>
                </div>
            </form>

            <?php if (isset($ip_id)) : ?>
                <div style="margin-top:20px;">
                    <a href="employer_dashboard.php?rem_ip_id=<?php echo $ip_id; ?>" onclick="return confirm('Do you really want to delete this internship?');" style="color:red; font-weight:bold;">
                        Delete Internship
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!--footer-->
    <footer>
        <?php include("footer.php"); ?>
    </footer>

</body>
</html>