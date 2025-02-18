<?php
session_start(); // Start the session

// Include the database connection
include('db_connection.php');

// Check if the login form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the input values
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username and password are provided
    if (empty($username) || empty($password)) {
        $_SESSION['message'] = 'Please enter both username and password.';
        $_SESSION['message_type'] = 'error';
    } else {
        // Sanitize input (for security)
        $username = $conn->real_escape_string($username);
        $password = $conn->real_escape_string($password);

        // Check if the username exists in the database
        $sql = "SELECT * FROM student WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Check if the password matches (assuming you have stored hashed passwords)
            if (password_verify($password, $user['password'])) {
                // Set session variables for the logged-in user
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                
                // Redirect to the voting page or any other page after successful login
                header('Location: student_visit.php'); // Assuming 'student_visit.php' is the next page
                exit;
            } else {
                $_SESSION['message'] = 'Incorrect password.';
                $_SESSION['message_type'] = 'error';
            }
        } else {
            $_SESSION['message'] = 'Username not found.';
            $_SESSION['message_type'] = 'error';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Basic styling for the login page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .login-form {
            width: 300px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .login-form h2 {
            text-align: center;
        }
        .login-form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .login-form button {
            width: 100%;
            padding: 10px;
            background-color: #004aad;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .login-form button:hover {
            background-color: #003b88;
        }
        .flash-message {
            text-align: center;
            padding: 10px;
            background-color: #ffcc00;
            color: #333;
            margin-bottom: 20px;
        }
        .flash-message.error {
            background-color: #dc3545;
            color: white;
        }
        .flash-message.success {
            background-color: #28a745;
            color: white;
        }
    </style>
</head>
<body>

    <!-- Display flash messages -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="flash-message <?= $_SESSION['message_type'] ?>">
            <?= $_SESSION['message'] ?>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <!-- Login Form -->
    <div class="login-form">
        <h2>Student Login</h2>
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Enter your username" required>
            <input type="password" name="password" placeholder="Enter your password" required>
            <button type="submit">Login</button>
        </form>
    </div>

</body>
</html>

<?php
// Close DB connection
$conn->close();
?>
