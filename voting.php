<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['vote'])) {
    $voter_id = $_POST['voter_id'];
    $candidate_id = $_POST['candidate_id'];

    // Check if voter has already voted
    $check = "SELECT * FROM votes WHERE voter_id = '$voter_id'";
    $check_result = $conn->query($check);

    if ($check_result->num_rows > 0) {
        echo "You have already voted.";
    } else {
        $sql = "INSERT INTO votes (voter_id, candidate_id) VALUES ('$voter_id', '$candidate_id')";
        if ($conn->query($sql) === TRUE) {
            // Increment candidate's vote count
            $update = "UPDATE candidates SET votes = votes + 1 WHERE id = '$candidate_id'";
            $conn->query($update);
            echo "Vote cast successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>
