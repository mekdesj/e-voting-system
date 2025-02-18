<?php
// Start session
session_start();

// Include database connection
include('db_connection.php');

// Define variables
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input values
    $fullname = mysqli_real_escape_string($conn, trim($_POST['fullname']));
    $userid = mysqli_real_escape_string($conn, trim($_POST['userid']));
    $password = trim($_POST['password']); // No need to escape as it's used in password_verify()

    // Check if the student exists in the database
    $query = "SELECT * FROM student WHERE full_name = '$fullname' AND user_id = '$userid'";
    $result = $conn->query($query);

    if ($result && $result->num_rows == 1) {
        // Fetch the student's data
        $row = $result->fetch_assoc();

        // Verify the password using password_verify
        if (password_verify($password, $row['password'])) {
            // Password matches, login successful
            $_SESSION['full_name'] = $fullname;
            $_SESSION['user_id'] = $userid;
            header("Location: student_visit.php");
            exit();
        } else {
            // Invalid password
            $error = "Invalid credentials. Please try again.";
        }
    } else {
        // No matching user found
        $error = "Invalid credentials. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            animation: fadeInBackground 1s ease-in-out;
        }

        .login-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 300px;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-group input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 4px rgba(0, 123, 255, 0.5);
        }

        .login-btn {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .login-btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInBackground {
            from {
                background-color: #e0e0e0;
            }

            to {
                background-color: #f4f4f9;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Student Login</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" required>
            </div>
            <div class="form-group">
                <label for="userid">User ID</label>
                <input type="text" id="userid" name="userid" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-btn">Login</button>
            <?php if (!empty($error)) { ?>
                <p class="error"><?= $error ?></p>
            <?php } ?>
        </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>
