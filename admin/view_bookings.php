<?php
session_start();
include '../config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    exit('Access denied');
}

// query using booking_details and registration table
$bk = $conn->query("
    SELECT 
        token_id, 
        passenger_name, 
        from_route, 
        to_route, 
        date AS travel_date, 
        metro_no 
    FROM booking_details
    ORDER BY token_id DESC
");



if (!$bk) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Bookings</title>
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
        .table th {
            white-space: nowrap;
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
            <h2 class="mb-4 text-primary">All Bookings</h2>

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Token</th>
                        <th>Passenger</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Date</th>
                        <th>Metro No</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($bk->num_rows > 0): ?>
                        <?php while($b = $bk->fetch_assoc()): ?>
                            <tr>
                                <td><?= $b['token_id'] ?></td>
                                <td><?= htmlspecialchars($b['passenger_name']) ?></td>
                                <td><?= htmlspecialchars($b['from_route']) ?></td>
                                <td><?= htmlspecialchars($b['to_route']) ?></td>
                                <td><?= $b['travel_date'] ?></td>
                                <td><?= $b['metro_no'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No bookings found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

</body>
</html>
