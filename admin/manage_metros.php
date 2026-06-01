<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
include '../config.php';

// Handle deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM metro WHERE metro_no = $id");
    $conn->query("DELETE FROM route WHERE metro_no = $id");
    header("Location: manage_metros.php");
    exit;
}

// Fetch metro data
$query = "
    SELECT m.metro_no, m.line, m.place_name, m.arr_time, m.dep_time,
           r.from_route, r.to_route
    FROM metro m
    LEFT JOIN route r ON m.metro_no = r.metro_no
    ORDER BY m.metro_no DESC
";
$metros = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Metros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .sidebar {
            background-color: #f8f9fa;
            min-height: 100vh;
            padding-top: 20px;
        }
        .sidebar a {
            display: block;
            padding: 10px 15px;
            color: #000;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #e2e6ea;
            color: #0056b3;
        }
        .table td, .table th {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
       <!-- Sidebar -->
            <div class="col-md-3 sidebar border-end">
                <h4 class="text-center text-primary">Admin Panel</h4>
                <a href="admin.php">Dashboard</a>
                <a href="add_metro.php">Add Metro Station</a>
                <a href="manage_metros.php">View/Edit/Delete Metros</a>
                <a href="view_bookings.php">View All Bookings</a>
                <hr>
                <a href="../index.php">← Back to Home</a>
                <a href="admin_logout.php" class="text-danger">Logout</a>
            </div>

        <!-- Main Content -->
        <div class="col-md-9 p-4">
            <h3 class="mb-4">Metro Station List</h3>

            <table class="table table-bordered table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Line</th>
                        <th>Place</th>
                        <th>Arrival</th>
                        <th>Departure</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $metros->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['metro_no'] ?></td>
                        <td><?= $row['line'] ?></td>
                        <td><?= $row['place_name'] ?></td>
                        <td><?= $row['arr_time'] ?></td>
                        <td><?= $row['dep_time'] ?></td>
                        <td><?= $row['from_route'] ?? '-' ?></td>
                        <td><?= $row['to_route'] ?? '-' ?></td>
                        <td>
                            <a href="edit_metro.php?id=<?= $row['metro_no'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                            <a href="manage_metros.php?delete=<?= $row['metro_no'] ?>"
                               onclick="return confirm('Delete this entry?')"
                               class="btn btn-sm btn-outline-danger ms-1">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
