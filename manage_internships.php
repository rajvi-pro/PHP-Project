<?php
session_start();
include("conn.php");

// ✅ ADD CATEGORY
if (isset($_POST['add_category'])) {
    $cat = trim($_POST['new_category']);
    if ($cat != "") {
        $stmt = $conn->prepare("INSERT INTO categories (category_name) VALUES (?)");
        $stmt->bind_param("s", $cat);
        $stmt->execute();
        header("Location: categories.php");
        exit;
    }
}

// ✅ UPDATE CATEGORY
if (isset($_POST['update_category'])) {
    $id = $_POST['cat_id'];
    $cat = trim($_POST['cat_name']);

    $stmt = $conn->prepare("UPDATE categories SET category_name=? WHERE id=?");
    $stmt->bind_param("si", $cat, $id);
    $stmt->execute();
    header("Location: categories.php");
    exit;
}

// ✅ DEACTIVATE CATEGORY
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $stmt = $conn->prepare("UPDATE categories SET status='inactive' WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: categories.php");
    exit;
}

// ✅ RESTORE CATEGORY
if (isset($_GET['restore'])) {
    $id = $_GET['restore'];

    $stmt = $conn->prepare("UPDATE categories SET status='active' WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: categories.php");
    exit;
}

// ✅ Fetch data for table
$catResult = mysqli_query($conn, "SELECT * FROM categories ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Categories</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    display: flex;
}
.sidebar {
    width: 240px;
    height: 100vh;
    background: #222;
    color: white;
    padding: 20px;
}
.sidebar a {
    color: white;
    display: block;
    padding: 10px;
    text-decoration: none;
    margin-bottom: 10px;
}
.sidebar a:hover {
    background: #444;
}
.content {
    flex: 1;
    padding: 30px;
}
</style>
</head>

<body>

<!-- ✅ SIDEBAR -->
<div class="sidebar">
    <h3>Admin Panel</h3>
    <a href="dashboard.php">Dashboard</a>
    <a href="internships.php">Internships</a>
    <a href="categories.php" style="background:#444;">Categories</a>
    <a href="logout.php">Logout</a>
</div>

<!-- ✅ MAIN CONTENT -->
<div class="content">
    <h2>Manage Categories</h2>
    <hr>

    <!-- ✅ Add Category -->
    <form method="POST" class="mb-4 d-flex" style="gap:10px; max-width:400px;">
        <input type="text" name="new_category" class="form-control" placeholder="Enter Category" required>
        <button type="submit" name="add_category" class="btn btn-success">Add</button>
    </form>

    <!-- ✅ Categories Table -->
    <table class="table table-bordered">
        <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Category Name</th>
            <th>Status</th>
            <th width="200">Actions</th>
        </tr>
        </thead>

        <tbody>
        <?php
        $i = 1;
        while ($row = mysqli_fetch_assoc($catResult)) { ?>
            <tr>
                <td><?= $i++ ?></td>

                <td>
                    <form method="POST" style="display:flex; gap:8px;">
                        <input type="hidden" name="cat_id" value="<?= $row['id'] ?>">
                        <input type="text" name="cat_name" value="<?= $row['category_name'] ?>" class="form-control">
                        <button type="submit" name="update_category" class="btn btn-primary btn-sm">Update</button>
                    </form>
                </td>

                <td>
                    <?php if ($row['status'] == "active") { ?>
                        <span class="badge bg-success">Active</span>
                    <?php } else { ?>
                        <span class="badge bg-danger">Inactive</span>
                    <?php } ?>
                </td>

                <td>
                    <?php if ($row['status'] == "active") { ?>
                        <a href="categories.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Deactivate</a>
                    <?php } else { ?>
                        <a href="categories.php?restore=<?= $row['id'] ?>" class="btn btn-success btn-sm">Restore</a>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

</div>
</body>
</html>
