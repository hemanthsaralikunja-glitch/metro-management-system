<?php
session_start();
include 'config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Only logged-in users can book
if (!isset($_SESSION['reg_id'])) {
    header("Location: login.php");
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $from = $_POST['from_route'];
    $to = $_POST['to_route'];
    $date = $_POST['date'];
    $reg_id = $_SESSION['reg_id'];
    $passenger_name = $_POST['passenger_name'];

    // Step 1: Find the metro number
    $stmt = $conn->prepare("SELECT metro_no FROM route WHERE from_route = ? AND to_route = ? LIMIT 1");
    $stmt->bind_param("ss", $from, $to);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        $metro = $row['metro_no'];

        // Step 2: Insert booking
        $sql = "INSERT INTO booking_details (passenger_name, reg_id, from_route, to_route, metro_no, date)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("❌ Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sissss", $passenger_name, $reg_id, $from, $to, $metro, $date);

        if ($stmt->execute()) {
            $last_id = $conn->insert_id;
            header("Location: download_ticket.php?token_id=$last_id");
            exit;
        } else {
            $message = "❌ Error: " . $stmt->error;
        }
    } else {
        $message = "❌ No metro route found between '$from' and '$to'.";
    }
}


$fromPlaces = $conn->query("SELECT DISTINCT from_route FROM route ORDER BY from_route ASC");
$toPlaces = $conn->query("SELECT DISTINCT to_route FROM route ORDER BY to_route ASC");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Book Metro Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }

        .card {
            border: none;
            border-radius: 12px;
        }

        .card-header {
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
        }

        button[type="submit"] {
            background-color: #28a745;
            border: none;
            font-weight: 500;
            padding: 10px 0;
        }

        button[type="submit"]:hover {
            background-color: #218838;
        }

        .btn-link {
            color: #007bff;
            font-size: 14px;
        }

        .btn-link:hover {
            text-decoration: underline;
        }

        .form-label {
            font-weight: 500;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card shadow">
                    <div class="card-header text-black text-center">
                        <h4 class="mb-0">Book a Metro Ticket</h4>
                    </div>
                    <div class="card-body p-4">

                        <?php if (!empty($message)): ?>
                            <div class="alert alert-danger"><?= $message; ?></div>
                        <?php endif; ?>

                        <form method="POST" action="book_ticket.php">
                            <div class="mb-3">
                                <label for="passenger_name" class="form-label">Passenger Name</label>
                                <input type="text" class="form-control" name="passenger_name" id="passenger_name"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="from_route" class="form-label">From</label>
                                <select class="form-select" name="from_route" id="from_route" required>
                                    <option value="">-- Select From --</option>
                                    <?php while ($row = $fromPlaces->fetch_assoc()): ?>
                                        <option value="<?= htmlspecialchars($row['from_route']) ?>">
                                            <?= htmlspecialchars($row['from_route']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="to_route" class="form-label">To</label>
                                <select class="form-select" name="to_route" id="to_route" required>
                                    <option value="">-- Select To --</option>
                                    <?php while ($row = $toPlaces->fetch_assoc()): ?>
                                        <option value="<?= htmlspecialchars($row['to_route']) ?>">
                                            <?= htmlspecialchars($row['to_route']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="date" class="form-label">Travel Date</label>
                                <input type="date" class="form-control" name="date" id="date" required>
                            </div>

                            <button type="submit" class="btn btn-success w-100"> Book Ticket</button>
                        </form>

                        <div class="text-center mt-3">
                            <a href="index.php" class="btn btn-link">← Back to Home</a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>