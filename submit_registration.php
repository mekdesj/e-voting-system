<?php
// Include the database connection file
include 'db_connection.php'; // Ensure this file exists in the same directory

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data safely and trim whitespace
    $full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    $student_id = isset($_POST['student_id']) ? trim($_POST['student_id']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

    // Validation: Check for empty fields
    if (empty($full_name) || empty($student_id) || empty($email) || empty($password) || empty($confirm_password)) {
        die("All fields are required!");
    }

    // Validation: Check if passwords match
    if ($password !== $confirm_password) {
        die("Passwords do not match!");
    }

    // Validation: Check for valid email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format!");
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check for duplicate student_id or email
    $check_sql = "SELECT * FROM student WHERE user_id = ? OR email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ss", $student_id, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die("A user with this Student ID or Email already exists!");
    }

    // Insert the user into the student table
    $insert_sql = "INSERT INTO student (full_name, user_id, email, password, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("ssss", $full_name, $student_id, $email, $hashed_password);

    if ($stmt->execute()) {
        // Redirect to student_visit.html after successful registration
        header("Location: student_visit.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
