<?php
session_start();
include("conn.php");

// Ensure admin is logged in
if (!isset($_SESSION['adminid'])) {
    header("Location: index.php");
    exit();
}

// Add category
if (isset($_POST['add_category'])) {
    $new_category = mysqli_real_escape_string($conn, $_POST['new_category']);

    // Check duplicate
    $check = mysqli_query($conn, "SELECT * FROM categories WHERE category_name='$new_category'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Category already exists!'); window.location='admin_dashboard.php#categories';</script>";
        exit();
    }

    // Insert new category
    $insert = mysqli_query($conn, "INSERT INTO categories (category_name) VALUES ('$new_category')");
    if ($insert) {
        echo "<script>alert('Category added successfully'); window.location='admin_dashboard.php#categories';</script>";
    } else {
        echo "<script>alert('Error adding category'); window.location='admin_dashboard.php#categories';</script>";
    }
}

// Soft delete category
if (isset($_GET['delete'])) {
    $cat_id = intval($_GET['delete']);
    $soft_delete = mysqli_query($conn, "UPDATE categories SET status='inactive' WHERE id='$cat_id'");
    if ($soft_delete) {
        echo "<script>alert('Category deleted successfully'); window.location='admin_dashboard.php#categories';</script>";
    } else {
        echo "<script>alert('Error deleting category'); window.location='admin_dashboard.php#categories';</script>";
    }
}

// Restore category
if (isset($_GET['restore'])) {
    $cat_id = intval($_GET['restore']);
    $restore = mysqli_query($conn, "UPDATE categories SET status='active' WHERE id='$cat_id'");
    if ($restore) {
        echo "<script>alert('Category restored successfully'); window.location='admin_dashboard.php#categories';</script>";
    } else {
        echo "<script>alert('Error restoring category'); window.location='admin_dashboard.php#categories';</script>";
    }
}
?>
