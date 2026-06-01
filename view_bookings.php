<?php
include 'config.php';
$result = $conn->query("SELECT * FROM booking_details");
?>

<h2>All Bookings</h2>
<table border="1">
<tr><th>Token ID</th><th>Passenger</th><th>Route</th><th>Metro No</th><th>Date</th></tr>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['token_id'] ?></td>
    <td><?= $row['pass_no'] ?></td>
    <td><?= $row['from_route'] ?> ➜ <?= $row['to_route'] ?></td>
    <td><?= $row['metro_no'] ?></td>
    <td><?= $row['date'] ?></td>
</tr>
<?php endwhile; ?>
</table>
