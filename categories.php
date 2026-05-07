<?php
session_start();
include("conn.php");

// ✅ Ensure admin login
if (!isset($_SESSION['adminid'])) {
    header("Location: index.php");
    exit();
}

// ✅ Handle Add Category
if (isset($_POST['add_category'])) {
    $new_cat = trim($_POST['new_category']);

    if ($new_cat != "") {
        mysqli_query($conn, "INSERT INTO categories (category_name) VALUES ('$new_cat')");
        $_SESSION['msg'] = "Category Added Successfully ✅";
    }
    header("Location: categories.php");
    exit();
}

// ✅ Handle Update
if (isset($_POST['update_category'])) {
    $id = $_POST['cat_id'];
    $name = trim($_POST['cat_name']);

    mysqli_query($conn, "UPDATE categories SET category_name='$name' WHERE id=$id");
    $_SESSION['msg'] = "Category Updated Successfully ✅";

    header("Location: categories.php");
    exit();
}

// ✅ Handle Soft Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "UPDATE categories SET status='inactive' WHERE id=$id");

    $_SESSION['msg'] = "Category Deactivated ❌";
    header("Location: categories.php");
    exit();
}

// ✅ Handle Restore
if (isset($_GET['restore'])) {
    $id = $_GET['restore'];
    mysqli_query($conn, "UPDATE categories SET status='active' WHERE id=$id");

    $_SESSION['msg'] = "Category Restored ✅";
    header("Location: categories.php");
    exit();
}

// Fetch Categories
$cats = mysqli_query($conn, "SELECT * FROM categories ORDER BY id DESC");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin | Categories</title>
    <link rel="icon" href="images/favicon.png">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
/* ✅ YOUR CSS EXACTLY ADDED */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    background: #f4f6f9;
}

/* Sidebar */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 230px;
    height: 100vh;
    background: #003499;
    color: white;
    display: flex;
    flex-direction: column;
}

.sidebar-header {
    text-align: center;
    padding: 25px 10px;
}

.sidebar-header h2 {
    margin: 10px 0;
    font-size: 19px;
    font-weight: bold;
}

.sidebar-menu {
    list-style: none;
    padding: 0;
    margin-top: 15px;
}

.sidebar-menu li {
    width: 100%;
}

.sidebar-menu a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px 22px;
    font-size: 16px;
    color: #ffffff;
    text-decoration: none;
    transition: 0.25s;
}

.sidebar-menu a:hover,
.sidebar-menu a.active {
    background: #005ce6;
    padding-left: 30px;
}

/* Topnav */
.topnav {
    margin-left: 230px;
    background: white;
    padding: 15px 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: space-between;
}

.topnav a {
    color: #003499;
    font-weight: bold;
    text-decoration: none;
}

/* Main Container */
.container {
    margin-left: 230px;
    padding: 30px;
}

h1 {
    text-align: center;
    margin-bottom: 25px;
    color: #333;
}

/* Category Section */
.category-box {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-top: 30px;
}

.category-box h2 {
    margin-bottom: 15px;
}

.add-category-form {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.add-category-form input {
    flex: 1;
    padding: 8px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.add-category-form button {
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    background: #0066ff;
    color: white;
    cursor: pointer;
}

.add-category-form button:hover {
    background: #004fcc;
}

table {
    width: 100%;
    border-collapse: collapse;
}

table th {
    background: #003499;
    color: white;
    padding: 12px;
}

table td {
    padding: 12px;
    border-bottom: 1px solid #eee;
}

table tr:hover {
    background: #f3f6ff;
}

/* Buttons */
.btn {
    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    color: white;
    transition: 0.3s;
}

.btn-update { background: #ffca2c; color: #2c2c2c; }
.btn-update:hover { background: #e7b323; }

.btn-delete { background: #ff4d4d; }
.btn-delete:hover { background: #cc0000; }

.btn-restore { background: #17b978; }
.btn-restore:hover { background: #118a5a; }

.category-input {
    padding: 6px;
    border-radius: 4px;
    border: 1px solid #ccc;
    width: 90%;
}

/* ✅ Toast Popup */
.toast {
    position: fixed;
    top: 20px;
    right: 25px;
    background: #003499;
    color: white;
    padding: 12px 20px;
    border-radius: 6px;
    display: none;
    animation: fadeInOut 3s ease;
}

@keyframes fadeInOut {
    0% {opacity: 0;}
    10% {opacity: 1;}
    90% {opacity: 1;}
    100% {opacity: 0;}
}
</style>

</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">
        <h2>Admin Panel</h2>
    </div>

    <ul class="sidebar-menu">
        <li><a href="admin_dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a></li>
        <li><a href="categories.php"><i class="fas fa-briefcase"></i>Categories</a></li>
        <li><a href="reports.php"><i class="fas fa-chart-line"></i> Reports</a></li>
        <li><a href="change_password.php"><i class="fas fa-key"></i> Change Password</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    
    </ul>
</div>

<div class="topnav">
    <span>Welcome Admin</span>
    <a href="logout.php">Logout</a>
</div>

<div class="container">

    <div class="category-box">
        <h2>Manage Categories</h2>

        <!-- ✅ Add Category -->
        <form method="POST" class="add-category-form">
            <input type="text" name="new_category" placeholder="Add new category" required>
            <button type="submit" name="add_category">Add Category</button>
        </form>

        <table>
            <tr>
                <th>#</th>
                <th>Category Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>

            <?php
            $i = 1;
            while ($cat = mysqli_fetch_assoc($cats)) {
            ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td>
                        <form method="POST" style="display:flex; gap:6px;">
                            <input type="hidden" name="cat_id" value="<?= $cat['id'] ?>">
                            <input type="text" name="cat_name" class="category-input" value="<?= htmlspecialchars($cat['category_name']) ?>">
                            <button type="submit" name="update_category" class="btn btn-update">Update</button>
                        </form>
                    </td>

                    <td><?= $cat['status'] == "active" ? "Active" : "Inactive" ?></td>

                    <td>
                        <?php if ($cat['status'] == "active") { ?>
                            <a class="btn btn-delete" href="categories.php?delete=<?= $cat['id'] ?>">Delete</a>
                        <?php } else { ?>
                            <a class="btn btn-restore" href="categories.php?restore=<?= $cat['id'] ?>">Restore</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>

<?php if (isset($_SESSION['msg'])) { ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const toast = document.createElement("div");
    toast.className = "toast";
    toast.innerText = "<?= $_SESSION['msg'] ?>";
    document.body.appendChild(toast);
    toast.style.display = "block";
    setTimeout(() => toast.remove(), 3000);
});
</script>
<?php unset($_SESSION['msg']); } ?>

</body>
</html>
