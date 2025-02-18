<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_candidate'])) {
    $name = $_POST['name'];
    $department = $_POST['department'];

    $sql = "INSERT INTO candidates (name, department) VALUES ('$name', '$department')";

    if ($conn->query($sql) === TRUE) {
        echo "Candidate added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    //candidate management/add candidate
}
?>
