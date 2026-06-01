<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}


$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $line = $_POST['line'];
    $place = $_POST['place_name'];
    $arr = $_POST['arr_time'];
    $dep = $_POST['dep_time'];
    $from = $_POST['from_route'];
    $to = $_POST['to_route'];

    // Step 1: insert into metro
    $stmt = $conn->prepare("INSERT INTO metro (line, place_name, arr_time, dep_time) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $line, $place, $arr, $dep);

    if ($stmt->execute()) {
        $metro_no = $conn->insert_id;

        // Step 2: insert into route
        $routeStmt = $conn->prepare("INSERT INTO route (metro_no, from_route, to_route) VALUES (?, ?, ?)");
        $routeStmt->bind_param("iss", $metro_no, $from, $to);

        if ($routeStmt->execute()) {
            $message = "✅ Metro added with route from $from to $to.";
        } else {
            $message = "❌ Metro added but route failed: " . $routeStmt->error;
        }
    } else {
        $message = "❌ Error adding metro: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Metro - Admin</title>
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
            color: #0d6efd;
        }
        .card {
            border-radius: 12px;
        }
        .card-header {
            background-color: black;
            color: white;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
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
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Add Metro</h5>
                </div>
                <div class="card-body">

                    <?php if (!empty($message)): ?>
                        <div class="alert alert-info"><?= $message; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="add_metro.php">
                        <div class="mb-3">
                            <label class="form-label">Line Number</label>
                            <input type="number" name="line" class="form-control" required placeholder="Enter line number">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Station / Place Name</label>
                            <input type="text" name="place_name" class="form-control" required placeholder="Enter station/place">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Arrival Time</label>
                            <input type="time" name="arr_time" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Departure Time</label>
                            <input type="time" name="dep_time" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">From Route</label>
                            <input type="text" name="from_route" class="form-control" required placeholder="Starting station">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">To Route</label>
                            <input type="text" name="to_route" class="form-control" required placeholder="Destination station">
                        </div>

                        <button type="submit" class="btn btn-success w-100">Add Metro</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
