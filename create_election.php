<?php
// Database connection (update with your database credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "voting";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success_message = "";
$error_message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    
    // Basic validation
    if (empty($title) || empty($start_date) || empty($end_date)) {
        $error_message = "Please fill in all required fields.";
    } elseif ($start_date > $end_date) {
        $error_message = "Start date must be before end date.";
    } else {
        // Insert election data into the database
        $stmt = $conn->prepare("INSERT INTO elections (title, description, start_date, end_date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $description, $start_date, $end_date);

        if ($stmt->execute()) {
            $success_message = "Election created successfully!";
        } else {
            $error_message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Election</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fb;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            color: #2b3e5e;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Form Styles */
        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
            color: #2b3e5e;
        }

        input[type="text"],
        input[type="date"],
        textarea {
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        textarea:focus {
            border-color: #007BFF;
            outline: none;
            background-color: #fff;
        }

        textarea {
            height: 120px;
        }

        /* Buttons */
        button {
            padding: 12px 20px;
            font-size: 16px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        button:focus {
            outline: none;
        }

        /* Messages */
        .success-message {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 16px;
            text-align: center;
        }

        .error-message {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 16px;
            text-align: center;
        }

        /* Responsive Layout */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            form {
                width: 100%;
            }

            button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create Election</h1>
        
        <?php if ($success_message): ?>
            <div class="success-message"><?= $success_message ?></div>
        <?php elseif ($error_message): ?>
            <div class="error-message"><?= $error_message ?></div>
        <?php endif; ?>
        
        <form method="POST" action="create_election.php">
            <label for="title">Election Title</label>
            <input type="text" id="title" name="title" required>

            <label for="description">Election Description</label>
            <textarea id="description" name="description"></textarea>

            <label for="start_date">Start Date</label>
            <input type="date" id="start_date" name="start_date" required>

            <label for="end_date">End Date</label>
            <input type="date" id="end_date" name="end_date" required>

            <button type="submit">Create Election</button>
        </form>
    </div>
</body>
</html>
