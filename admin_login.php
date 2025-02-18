<?php
// Start the session
session_start();

// Define database connection variables
$host = 'localhost';
$db_name = 'voting';
$username = 'root';  // Change this to your MySQL username
$password = '';      // Change this to your MySQL password

// Create a connection to the MySQL database
$conn = new mysqli($host, $username, $password, $db_name);

// Check if the connection was successful
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$errorMessage = '';

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the submitted username and password
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Check if the password is numeric and 4 digits long
    if (!preg_match('/^\d{4}$/', $pass)) {
        $errorMessage = 'Password must be exactly 4 digits.';
    } else {
        // Prepare and execute a query to check the credentials
        $stmt = $conn->prepare('SELECT * FROM admin WHERE username = ?');
        $stmt->bind_param('s', $user);
        $stmt->execute();
        $result = $stmt->get_result();

        // If a user is found
        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();

            // Verify the hashed password
            if (hash('sha256', $pass) === $admin['password']) {
                $_SESSION['admin'] = $admin['username'];  // Store username in session
                header('Location: admin_dashboard.php');  // Redirect to dashboard
                exit();
            } else {
                $errorMessage = 'Invalid credentials!';
            }
        } else {
            $errorMessage = 'No user found with that username!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
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
            border-color: #007bff;
            outline: none;
        }

        .login-btn {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
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
            display: none;
        }

        .error-message.show {
            display: block;
        }   /* Styling omitted for brevity, same as your provided code */
    </style>
</head>

<body>
    <div class="login-container">
        <h1>Admin Login</h1>
        <div class="error-message <?php echo $errorMessage ? 'show' : ''; ?>">
            <?php echo $errorMessage; ?>
        </div>
        <form id="login-form" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="password">Password (4 digits)</label>
                <input type="password" id="password" name="password" placeholder="Enter 4-digit password" 
                       pattern="\d{4}" title="Password must be exactly 4 digits" required>
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>
    </div>
</body>

</html>
