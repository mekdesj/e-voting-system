<?php
include 'db_connection.php';

$sql = "SELECT name, department, votes FROM candidates ORDER BY votes DESC";
$result = $conn->query($sql);

$results = [];
while ($row = $result->fetch_assoc()) {
    $results[] = $row;
}

echo json_encode($results);
?>
