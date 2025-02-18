<?php
include 'db_connection.php';

$sql = "SELECT * FROM candidates";
$result = $conn->query($sql);

$candidates = [];
while ($row = $result->fetch_assoc()) {
    $candidates[] = $row;
}

echo json_encode($candidates);
?>
