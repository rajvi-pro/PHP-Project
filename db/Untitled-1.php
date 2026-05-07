<?php
session_start();
include("conn.php");

// ✅ Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Handle delete user
if (isset($_GET['delete_user'])) {
    $uid = intval($_GET['delete_user']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $stmt->close();
    header("Location: nav.php?msg=User Deleted");
    exit();
}

// Handle delete client
if (isset($_GET['delete_client'])) {
    $cid = intval($_GET['delete_client']);
    $stmt = $conn->prepare("DELETE FROM clients WHERE id = ?");
    $stmt->bind_param("i", $cid);
    $stmt->execute();
    $stmt->close();
    header("Location: nav.php?msg=Client Deleted");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- ✅ NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link active" href="nav.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="user_add.php">Add User</a></li>
                    <li class="nav-item"><a class="nav-link" href="client_add.php">Add Client</a></li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link btn btn-danger btn-sm text-white" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ✅ MAIN CONTENT -->
    <div class="container mt-4">
        <h2 class="mb-4">Admin Dashboard</h2>
        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']); ?></div>
        <?php endif; ?>

        <!-- USERS TABLE -->
        <div class="card mb-4">
            <div class="card-header">Users</div>
            <div class="card-body">
                <a href="user_add.php" class="btn btn-success btn-sm mb-2">+ Add User</a>
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $conn->query("SELECT * FROM users");
                        while ($row = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= $row['id']; ?></td>
                            <td><?= htmlspecialchars($row['name']); ?></td>
                            <td><?= htmlspecialchars($row['email']); ?></td>
                            <td><?= htmlspecialchars($row['role']); ?></td>
                            <td>
                                <a href="user_edit.php?id=<?= $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="nav.php?delete_user=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this user?');">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- CLIENTS TABLE -->
        <div class="card">
            <div class="card-header">Clients</div>
            <div class="card-body">
                <a href="client_add.php" class="btn btn-success btn-sm mb-2">+ Add Client</a>
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Client Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $conn->query("SELECT * FROM clients");
                        while ($row = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= $row['id']; ?></td>
                            <td><?= htmlspecialchars($row['client_name']); ?></td>
                            <td><?= htmlspecialchars($row['email']); ?></td>
                            <td><?= htmlspecialchars($row['phone']); ?></td>
                            <td>
                                <a href="client_edit.php?id=<?= $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="nav.php?delete_client=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this client?');">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
