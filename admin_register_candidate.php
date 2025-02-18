<?php
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "voting";

$message = "";
$messageType = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize input
    $username = $_POST['username'];
    $password = $_POST['password'];
    $full_name = $_POST['full_name'];
    $candidate_details = $_POST['candidate_details'];
    $campaign = $_POST['campaign'];
    $department = $_POST['department'];

    // Validate full name
    if (!preg_match('/^[a-zA-Z\s]+$/', $full_name)) {
        $message = "Full name can only contain letters and spaces.";
        $messageType = "error";
    } 
    // Validate department
    elseif (!preg_match('/^[a-zA-Z\s]+$/', $department)) {
        $message = "Department can only contain letters.";
        $messageType = "error";
    } 
    // Validate password
    elseif (strlen($password) < 7) {
        $message = "Password must be at least 7 characters.";
        $messageType = "error";
    } else {
        $password = password_hash($password, PASSWORD_DEFAULT); // Hash password securely

        // Ensure 'uploads/' directory exists
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Handle file upload
        $target_file = $target_dir . basename($_FILES["candidate_picture"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (!getimagesize($_FILES["candidate_picture"]["tmp_name"])) {
            $message = "The file is not a valid image.";
            $messageType = "error";
        } elseif ($_FILES["candidate_picture"]["size"] > 4000000) {
            $message = "File size exceeds 4MB.";
            $messageType = "error";
        } elseif (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            $message = "Only JPG, JPEG, PNG, and GIF files are allowed.";
            $messageType = "error";
        } elseif (!move_uploaded_file($_FILES["candidate_picture"]["tmp_name"], $target_file)) {
            $message = "Error uploading the file.";
            $messageType = "error";
        } else {
            // Insert into database using prepared statements
            $stmt = $conn->prepare("INSERT INTO candidate (username, password, full_name, candidate_details, campaign, department, candidate_picture) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $username, $password, $full_name, $candidate_details, $campaign, $department, $target_file);

            if ($stmt->execute()) {
                $message = "Candidate \"$full_name\" registered successfully!";
                $messageType = "success";
            } else {
                $message = "Database error: " . $stmt->error;
                $messageType = "error";
            }

            $stmt->close();
        }
    }

    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Candidate</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f8ff;
            animation: fadeIn 1.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        header {
            background-color: #004aad;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        header h1 {
            margin: 10px 0;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            text-decoration: none;
            color: #fff;
            padding: 10px 20px;
            border-radius: 20px;
            background: linear-gradient(90deg, #004aad, #007bff);
            transition: all 0.3s ease;
        }

        nav ul li a:hover {
            background: linear-gradient(90deg, #003580, #0056b3);
            transform: scale(1.1);
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        main {
            padding: 20px;
        }

        h2 {
            color: #004aad;
            margin-bottom: 20px;
        }

        .section {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 700px;
            margin: 20px auto;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #004aad;
        }

        input[type="text"],
        input[type="password"],
        textarea,
        input[type="file"] {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
            font-size: 14px;
        }

        button {
            padding: 12px 25px;
            font-size: 1.1rem;
            font-weight: bold;
            color: white;
            background: linear-gradient(90deg, #007bff, #0056b3);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.1);
        }

        button:hover {
            background: linear-gradient(90deg, #0056b3, #003580);
            transform: scale(1.05);
        }

        footer {
            background-color: #0056b3;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: bold;
        }

        .success {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }

        .error {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>
    <header>
        <h1>Register Candidate</h1>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="admin_vote_viewer.php">View Voting Process</a></li>
                <li><a href="admin_result.php">Get Results</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="section">
            <h2>Register a Candidate</h2>

            <!-- Display message here -->
            <?php if (!empty($message)) { ?>
                <div class="message <?php echo $messageType; ?>"><?php echo $message; ?></div>
            <?php } ?>

            <form method="POST" enctype="multipart/form-data">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter candidate's username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter candidate's password" 
                       pattern=".{7,}" title="Password must be at least 7 characters long" required>

                <label for="full_name">Full Name:</label>
                <input type="text" id="full_name" name="full_name" placeholder="Enter candidate's full name" 
                       pattern="[A-Za-z\s]+" title="Full name can only contain letters and spaces" required>

                <label for="candidate_details">Candidate Details:</label>
                <textarea id="candidate_details" name="candidate_details" rows="5" placeholder="Enter candidate's details" required></textarea>

                <label for="campaign">Campaign Information:</label>
                <textarea id="campaign" name="campaign" rows="5" placeholder="Enter candidate's campaign information" required></textarea>

                <label for="candidate_picture">Candidate Picture:</label>
                <input type="file" id="candidate_picture" name="candidate_picture" accept="image/*" required>

                <label for="department">Department:</label>
                <input type="text" id="department" name="department" placeholder="Enter candidate's department" 
                       pattern="[A-Za-z\s]+" title="Department can only contain letters and spaces" required>

                <button type="submit">Register Candidate</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Admin Dashboard | All Rights Reserved</p>
    </footer>
</body>

</html>
