<?php 
session_start();
include("conn.php");

if(!isset($_SESSION['sid'])){
    header("Location: index.php");
    exit();
}

$sid = $_SESSION['sid'];
$query = "SELECT * FROM student_info WHERE sid=$sid";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_assoc($result);
    $oldfname = $row['stu_fname'];
    $oldlname = $row['stu_lname'];
    $oldgender = $row['gender'];
    $oldcity = $row['city'];
    $oldemail = $row['stu_email'];
    $oldphone = $row['stu_phone'];
    $oldDP = $row['dp_path'] ?: 'uploads/dp/blank.jpg';
}

// =============================
// HANDLE PROFILE PICTURE UPLOAD
// =============================
if(isset($_POST['dpUpload'])){
    if(isset($_FILES['dpFile']) && $_FILES['dpFile']['size'] > 0){
        $targetDir = "uploads/dp/";
        $fileName = "dp_".$sid."_".time();
        $fileType = strtolower(pathinfo($_FILES['dpFile']['name'], PATHINFO_EXTENSION));
        $targetFile = $targetDir.$fileName.".".$fileType;
        $allowed = ['jpg','jpeg','png'];

        if($_FILES['dpFile']['size'] > 1048576){
            echo "<script>alert('File too large. Max 1MB.');</script>";
        } else if(!in_array($fileType, $allowed)){
            echo "<script>alert('Invalid file type. JPG/JPEG/PNG only.');</script>";
        } else {
            if(move_uploaded_file($_FILES['dpFile']['tmp_name'], $targetFile)){
                if(file_exists($oldDP) && $oldDP != 'uploads/dp/blank.jpg') unlink($oldDP);
                mysqli_query($conn, "UPDATE student_info SET dp_path='$targetFile' WHERE sid=$sid");
                echo "<script>alert('Profile picture updated'); window.location='student_profile.php';</script>";
            } else {
                echo "<script>alert('Failed to upload picture');</script>";
            }
        }
    }
}

// =============================
// HANDLE SAVE PROFILE
// =============================
if(isset($_POST['saveProfile'])){
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);

    $update = "UPDATE student_info SET stu_fname='$fname', stu_lname='$lname', stu_phone='$phone', gender='$gender', city='$city' WHERE sid=$sid";
    if(mysqli_query($conn, $update)){
        echo "<script>alert('Profile saved successfully'); window.location='student_profile.php';</script>";
    } else {
        echo "<script>alert('Failed to save profile');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Profile | Internshop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family:'Poppins',sans-serif; margin:0; padding:0; background:#f5f6fa; color:#333; }
        header, footer { text-align:center; padding:15px; background:#fff; box-shadow:0 2px 6px rgba(0,0,0,0.1);}
        .profile-container { max-width:900px; margin:40px auto; background:#fff; border-radius:15px; padding:30px; box-shadow:0 10px 25px rgba(0,0,0,0.05);}
        .title h2 { text-align:center; font-weight:700; margin-bottom:30px; }
        .stu-profile-container { display:flex; flex-wrap:wrap; gap:20px; align-items:flex-start; }
        .dp-div { flex:0 0 180px; text-align:center; }
        .dp-img { width:150px; height:150px; border-radius:50%; object-fit:cover; border:4px solid #5563DE;}
        .border1, .border2 { flex:1; min-width:250px; background:#f9f9f9; border-radius:10px; padding:20px; box-shadow:0 5px 15px rgba(0,0,0,0.05);}
        .profile-label { display:flex; align-items:center; font-weight:500; margin-top:15px; margin-bottom:5px; }
        .profile-label i { margin-right:8px; color:#5563DE; }
        .profile-input { width:100%; padding:10px; border-radius:8px; border:1px solid #ccc; font-size:14px; background:#e9ecef; color:#777; }
        .profile-input:focus { outline:none; border:2px solid #5563DE; background:#fff; }
        .btn-edit, .btn-dp, .btn-save { margin-top:20px; padding:12px 20px; border-radius:8px; border:none; font-weight:600; cursor:pointer; }
        .btn-edit { background:#5563DE; color:#fff; }
        .btn-edit:hover { background:#3f4cb7; }
        .btn-dp { background:#17a2b8; color:#fff; }
        .btn-dp:hover { background:#138496; }
        .btn-save { background:#28a745; color:#fff; }
        .btn-save:hover { background:#218838; }
        @media (max-width:768px){ .stu-profile-container { flex-direction:column; align-items:center; } .border1,.border2 { width:90%; } }
    </style>
</head>
<body>
<header>
    <?php include("nav.php"); ?>
</header>

<main>
    <div class="profile-container">
        <div class="title">
            <h2><i class="fa-solid fa-user"></i> Student Profile</h2>
        </div>

        <form method="POST" id="profileForm" enctype="multipart/form-data">
            <div class="stu-profile-container">
                <div class="dp-div">
                    <img class="dp-img" src="<?php echo $oldDP; ?>" alt="Profile Picture">
                    <input type="file" name="dpFile" accept=".jpg,.jpeg,.png" style="margin-top:10px;">
                    <button type="submit" name="dpUpload" class="btn-dp"><i class="fa-solid fa-upload"></i> Upload</button>
                </div>

                <div class="border1">
                    <label class="profile-label"><i class="fa-solid fa-user"></i> First Name</label>
                    <input class="profile-input" type="text" name="fname" value="<?php echo $oldfname; ?>">

                    <label class="profile-label"><i class="fa-solid fa-envelope"></i> Email</label>
                    <input class="profile-input" type="email" value="<?php echo $oldemail; ?>" disabled>

                    <label class="profile-label"><i class="fa-solid fa-city"></i> City</label>
                    <input class="profile-input" type="text" name="city" value="<?php echo $oldcity; ?>">
                </div>

                <div class="border2">
                    <label class="profile-label"><i class="fa-solid fa-user"></i> Last Name</label>
                    <input class="profile-input" type="text" name="lname" value="<?php echo $oldlname; ?>">

                    <label class="profile-label"><i class="fa-solid fa-phone"></i> Phone</label>
                    <input class="profile-input" type="text" name="phone" value="<?php echo $oldphone; ?>">

                    <label class="profile-label"><i class="fa-solid fa-venus-mars"></i> Gender</label>
                    <select class="profile-input" name="gender">
                        <option value="Male" <?php if($oldgender=='Male') echo 'selected'; ?>>Male</option>
                        <option value="Female" <?php if($oldgender=='Female') echo 'selected'; ?>>Female</option>
                    </select>
                </div>
            </div>

            <div style="text-align:center; margin-top:20px;">
                <button type="button" class="btn-edit" id="editBtn"><i class="fa-solid fa-pen-to-square"></i> Edit Profile</button>
                <button type="submit" name="saveProfile" class="btn-save" id="saveBtn"><i class="fa-solid fa-floppy-disk"></i> Save Profile</button>
            </div>
        </form>
    </div>
</main>

<footer>
    <?php include("footer.php"); ?>
</footer>

<script>
document.addEventListener("DOMContentLoaded", function(){
    const editBtn = document.getElementById("editBtn");
    const saveBtn = document.getElementById("saveBtn");
    const inputs = document.querySelectorAll("#profileForm .profile-input");

    // Initially disable editable inputs except email
    inputs.forEach(input => { if(input.name !== 'fname' && input.name !== 'lname' && input.name !== 'city' && input.name !== 'phone' && input.name !== 'gender') return; input.disabled = true; });
    saveBtn.disabled = true;

    editBtn.addEventListener("click", function(){
        if(confirm("You are about to edit your profile now. Proceed?")){
            inputs.forEach(input => { if(input.name !== 'email') input.disabled = false; input.style.border='2px solid #5563DE'; });
            saveBtn.disabled = false;
        }
    });
});
</script>
</body>
</html>
