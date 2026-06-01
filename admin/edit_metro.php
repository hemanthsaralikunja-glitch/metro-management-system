<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
include '../config.php';

$message = '';

// Validate metro ID
if (!isset($_GET['id'])) {
    echo "❌ Metro ID missing.";
    exit;
}
$id = $_GET['id'];

// Handle update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $line = $_POST['line'];
    $place = $_POST['place_name'];
    $arr = $_POST['arr_time'];
    $dep = $_POST['dep_time'];
    $from = $_POST['from_route'];
    $to = $_POST['to_route'];

    $stmt = $conn->prepare("UPDATE metro SET line=?, place_name=?, arr_time=?, dep_time=? WHERE metro_no=?");
    $stmt->bind_param("isssi", $line, $place, $arr, $dep, $id);
    $stmt->execute();

    $routeStmt = $conn->prepare("UPDATE route SET from_route=?, to_route=? WHERE metro_no=?");
    $routeStmt->bind_param("ssi", $from, $to, $id);

    if ($stmt && $routeStmt->execute()) {
        header("Location: manage_metros.php?msg=Metro+Updated");
        exit;
    } else {
        $message = "❌ Update failed.";
    }
}

// Fetch existing data
$stmt = $conn->prepare("
    SELECT m.*, r.from_route, r.to_route
    FROM metro m
    LEFT JOIN route r ON m.metro_no = r.metro_no
    WHERE m.metro_no = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if (!$result) {
    echo "❌ Metro not found.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Metro - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
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
            border: none;
            border-radius: 12px;
        }
        .card-header {
            background-color: #0d6efd;
            color: white;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .form-control {
            border-radius: 8px;
        }
        button[type="submit"] {
            background-color: #198754;
            border: none;
        }
        button[type="submit"]:hover {
            background-color: #157347;
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
                <a href="add_route.php">Define Metro Route</a>
                <a href="view_bookings.php">View All Bookings</a>
                <hr>
                <a href="../index.php">← Back to Home</a>
                <a href="admin_logout.php" class="text-danger">Logout</a>
            </div>

        <!-- Main Content -->
        <div class="col-md-9 p-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Edit Metro Entry #<?= $id ?></h5>
                </div>
                <div class="card-body">

                    <?php if (!empty($message)): ?>
                        <div class="alert alert-danger"><?= $message; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Line Number</label>
                            <input type="number" name="line" class="form-control" required value="<?= $result['line'] ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Station / Place Name</label>
                            <input type="text" name="place_name" class="form-control" required value="<?= $result['place_name'] ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Arrival Time</label>
                            <input type="time" name="arr_time" class="form-control" required value="<?= $result['arr_time'] ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Departure Time</label>
                            <input type="time" name="dep_time" class="form-control" required value="<?= $result['dep_time'] ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">From Station</label>
                            <input type="text" name="from_route" class="form-control" required value="<?= $result['from_route'] ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">To Station</label>
                            <input type="text" name="to_route" class="form-control" required value="<?= $result['to_route'] ?>">
                        </div>

                        <button type="submit" class="btn btn-success w-100">Update Metro</button>
                    </form>


                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
