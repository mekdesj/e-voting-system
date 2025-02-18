<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

// Assuming database connection is established in db_connection.php
include('db_connection.php');

// Define error message
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get username and password from form submission
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check the credentials in the election_officer table
    $query = "SELECT * FROM election_officer WHERE username = '$username' AND password = '$password' LIMIT 1";
    $result = $conn->query($query);

    if ($result && $result->num_rows == 1) {
        // Fetch the user data from the result
        $row = $result->fetch_assoc();

        // Login successful
        $_SESSION['username'] = $row['username'];
        $_SESSION['user_id'] = $row['id'];  // Store the user ID
        header('Location: manage_admin.php'); // Redirect to the admin page
        exit();
    } else {
        // Invalid credentials
        $errorMessage = 'Invalid username or password.';
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Officer Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: #ffffff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        .login-container h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #0056b3;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #0056b3;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-group input:focus {
            border-color: #0056b3;
            outline: none;
        }

        .login-btn {
            width: 100%;
            padding: 10px;
            background-color: #0056b3;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .login-btn:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h1>Election Officer Login</h1>
        <!-- Error message will be displayed if login fails -->
        <?php if ($errorMessage): ?>
            <div class="error-message"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>
    </div>
</body>

</html>
