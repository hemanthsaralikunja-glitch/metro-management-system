<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
include '../config.php'; // Make sure DB is connected

// Count total metros
$metroCount = $conn->query("SELECT COUNT(*) AS total FROM metro")->fetch_assoc()['total'];

// Count total routes
$routeCount = $conn->query("SELECT COUNT(*) AS total FROM route")->fetch_assoc()['total'];

// Count total bookings
$bookingCount = $conn->query("SELECT COUNT(*) AS total FROM booking_details")->fetch_assoc()['total'];

?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard - Metro Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
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
                <h2>Welcome, Admin</h2>
                <p class="lead mb-4">Use the sidebar to manage metro operations efficiently.</p>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card border-start border-primary border-4 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Total Metros</h5>
                                <h3 class="text-primary"><?= $metroCount ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-start border-success border-4 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Total Routes</h5>
                                <h3 class="text-success"><?= $routeCount ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-start border-warning border-4 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Total Bookings</h5>
                                <h3 class="text-warning"><?= $bookingCount ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>