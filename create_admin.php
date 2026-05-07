<?php
include("conn.php");

$username = "admin";       // desired username
$email = "admin@gmail.com"; // desired email
$password = "admin@123";    // desired password

$hashed = password_hash($password, PASSWORD_DEFAULT);

// Check if admin exists
$check = mysqli_query($conn, "SELECT * FROM admin WHERE email='$email' OR username='$username'");
if (mysqli_num_rows($check) > 0) {
    // Admin exists, update password
    $update = mysqli_query($conn, "UPDATE admin SET password='$hashed' WHERE email='$email'");
    echo "✅ Admin exists. Password updated successfully!<br>";
    echo "Username: $username<br>";
    echo "Password: $password<br>";
} else {
    // Admin does not exist, insert new
    $sql = "INSERT INTO admin (username, email, password) VALUES ('$username', '$email', '$hashed')";
    if (mysqli_query($conn, $sql)) {
        echo "✅ New admin created successfully!<br>";
        echo "Username: $username<br>";
        echo "Password: $password<br>";
    } else {
        echo "❌ Error: " . mysqli_error($conn);
    }
}
?>
