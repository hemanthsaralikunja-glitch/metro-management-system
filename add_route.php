<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $metro = $_POST['metro_no'];
    $from = $_POST['from_route'];
    $to = $_POST['to_route'];
    $line = $_POST['line'];

    $stmt = $conn->prepare("INSERT INTO route (metro_no, from_route, to_route, line) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $metro, $from, $to, $line);
    $stmt->execute();

    echo "Route Added!";
}
?>
