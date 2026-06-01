<!-- update_location.php -->
<?php
include '../config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $metro_no = $_POST['metro_no'];
    $lat = $_POST['latitude'];
    $lng = $_POST['longitude'];
    $now = date("Y-m-d H:i:s");

    $stmt = $conn->prepare("REPLACE INTO metro_tracking (metro_no, latitude, longitude, last_updated) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idds", $metro_no, $lat, $lng, $now);
    $stmt->execute();
    echo "✅ Location updated.";
}
?>
