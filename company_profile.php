<?php
session_start();
include("conn.php"); // MUST HAVE $conn connection

// Redirect if not logged in
if (!isset($_SESSION['eid'])) {
    header("Location: index.php");
    exit();
}

$eid = intval($_SESSION['eid']);

// Default empty values
$oldfname = $oldlname = $oldemail = $oldphone = $oldcomname = $oldcomlink = $oldcomabout = $oldaddress = $oldDP = $status = "";

// Default DP filename
$compDPName = strtolower($eid);

// Fetch data
$query = "SELECT emp_fname, emp_lname, emp_email, emp_phone, com_name, com_address, com_link, com_about, com_dp_path, status 
          FROM employer_info WHERE eid = $eid";

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    $oldfname   = $row["emp_fname"];
    $oldlname   = $row["emp_lname"];
    $oldemail   = $row["emp_email"];
    $oldphone   = $row["emp_phone"];
    $oldcomname = $row["com_name"];
    $oldcomlink = $row["com_link"];
    $oldcomabout= $row["com_about"];
    $oldaddress = $row["com_address"];
    $oldDP      = $row["com_dp_path"];
    $status     = $row["status"];

    $compDPName = strtolower($eid . "_" . $oldfname . "_" . $oldlname);
} else {
    $oldDP = "uploads/com/blank.png";
}

/* ✅ SAVE PROFILE */
if (isset($_POST["save"])) {

    $fname      = mysqli_real_escape_string($conn, $_POST["fname"]);
    $lname      = mysqli_real_escape_string($conn, $_POST["lname"]);
    $com_name   = mysqli_real_escape_string($conn, $_POST["com_name"]);
    $com_address= mysqli_real_escape_string($conn, $_POST["com_address"]);
    $com_link   = mysqli_real_escape_string($conn, $_POST["com_link"]);
    $com_about  = mysqli_real_escape_string($conn, $_POST["com_about"]);
    $phone      = mysqli_real_escape_string($conn, $_POST["phone"]);

    $update = "UPDATE employer_info SET
                com_name = '$com_name',
                com_address = '$com_address',
                com_link = '$com_link',
                com_about = '$com_about',
                emp_fname = '$fname',
                emp_lname = '$lname',
                emp_phone = '$phone',
                status = 'verified'
               WHERE eid = $eid";

    if (mysqli_query($conn, $update)) {
        echo "<script>alert('Profile Updated Successfully'); location.replace('company_profile.php');</script>";
        exit();
    } else {
        echo "<script>alert('Failed to update profile');</script>";
    }
}

/* ✅ UPLOAD COMPANY LOGO */
if (isset($_POST["dpUpload"])) {

    if (!empty($_FILES["dpFile"]["name"])) {

        $targetDir = "uploads/com/";
        $allowedFileTypes = ["jpg", "jpeg", "png", "webp"];
        $maxSize = 2 * 1024 * 1024; // 2 MB limit

        $originalName = basename($_FILES["dpFile"]["name"]);
        $fileType = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $fileSize = $_FILES["dpFile"]["size"];

        if ($fileSize > $maxSize) {
            echo "<script>alert('Max file size allowed is 2 MB');</script>";
        } elseif (!in_array($fileType, $allowedFileTypes)) {
            echo "<script>alert('Only JPG/JPEG/PNG/WEBP allowed');</script>";
        } else {
            $fileName = $compDPName . "_" . time() . "." . $fileType;
            $targetFilePath = $targetDir . $fileName;

            if (move_uploaded_file($_FILES["dpFile"]["tmp_name"], $targetFilePath)) {

                $update = "UPDATE employer_info SET com_dp_path = '$targetFilePath' WHERE eid = $eid";

                if (mysqli_query($conn, $update)) {
                    if (!empty($oldDP) && $oldDP !== "uploads/com/blank.png" && file_exists($oldDP)) {
                        unlink($oldDP); // delete old DP
                    }

                    echo "<script>alert('Logo uploaded successfully'); location.replace('company_profile.php');</script>";
                    exit();
                } else {
                    unlink($targetFilePath);
                    echo "<script>alert('Database update failed');</script>";
                }
            } else {
                echo "<script>alert('Logo upload failed');</script>";
            }
        }
    } else {
        echo "<script>alert('Please select a file');</script>";
    }
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Internshop | Company profile</title>
    <link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <style>
        body{ margin:0; font-family: Arial; background:#eef3ff; }
        .container{ width:950px; max-width:95%; margin:40px auto; background:#fff; border-radius:12px; padding:25px; display:flex; gap:30px; box-shadow:0 0 15px rgba(0,0,0,0.12); }
        .company-left{ width:30%; text-align:center; }
        .company-img{ width:160px; height:160px; border-radius:10px; border:2px solid #ddd; object-fit:cover; }
        .file-label{ background:#0066ff; color:#fff; padding:8px 12px; border-radius:6px; cursor:pointer; font-size:14px; display:inline-block; }
        .choose-wrapper{ margin-top:12px; display:flex; justify-content:center; gap:8px; align-items:center; }
        .file-name{ font-size:13px; color:#444; max-width:140px; overflow:hidden; white-space:nowrap; text-overflow:ellipsis; }
        .upload-btn{ background:#28a745; color:#fff; width:100%; border:none; padding:8px; margin-top:8px; border-radius:6px; cursor:pointer; }
        .company-right{ width:70%; }
        .row{ display:flex; gap:20px; margin-bottom:12px; }
        .form-group{ flex:1; }
        .company-input, .company-textarea{ width:100%; padding:10px; border:1px solid #aaa; border-radius:6px; font-size:14px; }
        .company-textarea{ height:120px; resize:none; }
        .save-btn{ width:100%; padding:12px; color:#fff; border:none; border-radius:6px; margin-top:12px; cursor:pointer; font-size:16px; background:#0066ff; }
        label i{ color:#0066ff; margin-right:6px; }
    </style>
</head>
<body>

<header>
    <?php include("nav.php"); ?>
</header>

<div class="container">

    <!-- LEFT SIDE → Logo -->
    <div class="company-left">

        <img src="<?php echo htmlspecialchars($oldDP ?: 'uploads/com/blank.png'); ?>" class="company-img"
             onerror="this.src='uploads/com/blank.png';">

        <form method="POST" enctype="multipart/form-data" style="margin-top:14px;">
            <div class="choose-wrapper">
                <label class="file-label">
                    <i class="fa-solid fa-image"></i> Choose Logo
                    <input type="file" id="dpFile" name="dpFile" accept=".jpg,.jpeg,.png,.webp" style="display:none;">
                </label>
                <span id="fileName" class="file-name">No File Selected</span>
            </div>

            <button type="submit" class="upload-btn" name="dpUpload">
                <i class="fa-solid fa-upload"></i> Upload
            </button>
        </form>
    </div>

    <!-- RIGHT SIDE → Profile Form -->
    <div class="company-right">
        <form method="POST">

            <h2 style="align:center;">Company Profile</h2>



            <div class="row">
                <div class="form-group">
                    <label><i class="fa-solid fa-user"></i> First Name</label>
                    <input type="text" class="company-input" name="fname" value="<?php echo htmlspecialchars($oldfname); ?>">
                </div>

                <div class="form-group">
                    <label><i class="fa-solid fa-user"></i> Last Name</label>
                    <input type="text" class="company-input" name="lname" value="<?php echo htmlspecialchars($oldlname); ?>">
                </div>
            </div>

            <div class="row">
                <div class="form-group">
                    <label><i class="fa-solid fa-building"></i> Company Name</label>
                    <input type="text" class="company-input" name="com_name" value="<?php echo htmlspecialchars($oldcomname); ?>">
                </div>

                <div class="form-group">
                    <label><i class="fa-solid fa-phone"></i> Phone</label>
                    <input type="text" class="company-input" name="phone" value="<?php echo htmlspecialchars($oldphone); ?>">
                </div>
            </div>

            <label><i class="fa-solid fa-location-dot"></i> Company Address</label>
            <input type="text" class="company-input" name="com_address" value="<?php echo htmlspecialchars($oldaddress); ?>"><br><br>

            <label><i class="fa-solid fa-globe"></i> Website</label>
            <input type="text" class="company-input" name="com_link" value="<?php echo htmlspecialchars($oldcomlink); ?>"><br><br>

            <label><i class="fa-solid fa-align-left"></i> About Company</label>
            <textarea class="company-textarea" name="com_about"><?php echo htmlspecialchars($oldcomabout); ?></textarea>

            <button type="submit" class="save-btn" name="save">
                <i class="fa-solid fa-floppy-disk"></i> Save Profile
            </button>
        </form>
    </div>
</div>

<footer>
    <?php include("footer.php"); ?>
</footer>

<script>
    document.getElementById('dpFile').addEventListener('change', function () {
        if (this.files && this.files[0]) {
            document.getElementById('fileName').innerText = this.files[0].name;
        }
    });
</script>

</body>
</html>
