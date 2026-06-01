<?php
include 'config.php';
$result = $conn->query("SELECT * FROM passenger");
?>

<h2>Passengers</h2>
<table border="1">
<tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th></tr>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['pass_id'] ?></td>
    <td><?= $row['name'] ?></td>
    <td><?= $row['email'] ?></td>
    <td><?= $row['phone'] ?></td>
</tr>
<?php endwhile; ?>
</table>
