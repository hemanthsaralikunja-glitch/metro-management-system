<?php
include 'config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['token_id'])) {
    die("Ticket info missing.");
}

$token_id = $_GET['token_id'];

$stmt = $conn->prepare("
    SELECT 
        passenger_name, 
        from_route, 
        to_route, 
        metro_no, 
        date 
    FROM booking_details 
    WHERE token_id = ?
");
$stmt->bind_param("i", $token_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Ticket not found.");
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Metro Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .ticket-card {
            max-width: 500px;
            margin: auto;
            margin-top: 60px;
            padding: 30px;
            border: 1px solid #dee2e6;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="ticket-card" id="ticket">
    <h3 class="mb-4 text-center text-primary">Metro Ticket</h3>

    <p><strong>Token ID:</strong> <?= htmlspecialchars($token_id) ?></p>
    <p><strong>Passenger:</strong> <?= htmlspecialchars($row['passenger_name']) ?></p>
    <p><strong>From:</strong> <?= htmlspecialchars($row['from_route']) ?></p>
    <p><strong>To:</strong> <?= htmlspecialchars($row['to_route']) ?></p>
    <p><strong>Metro No:</strong> <?= htmlspecialchars($row['metro_no']) ?></p>
    <p><strong>Date:</strong> <?= htmlspecialchars($row['date']) ?></p>

    <div class="text-center mt-4 no-print">
        <a href="index.php" class="btn btn-secondary">Back to Home</a>
        <button onclick="window.print()" class="btn btn-success ms-2">Download Ticket</button>
    </div>
</div>

</body>
</html>
