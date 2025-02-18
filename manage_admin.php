<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admin Activities</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #0862bd;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label,
        .form-group select,
        .form-group input,
        .form-group button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
        }

        .form-group button {
            background-color: #0862bd;
            color: white;
            border: none;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #064b8e;
        }

        nav a {
            display: block;
            margin-top: 10px;
            text-align: center;
            text-decoration: none;
            color: #0862bd;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Manage Admin Activities</h1>
        <?php
        // Database connection details
        $host = 'localhost'; // Update as needed
        $user = 'root'; // Replace with your database username
        $password = ''; // Replace with your database password
        $dbname = 'voting'; // Replace with your database name

        // Create a connection
        $conn = new mysqli($host, $user, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("<p style='color: red;'>Connection failed: " . $conn->connect_error . "</p>");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Capture form data
            $admin_action = $_POST['admin_action'];
            $admin_name = $_POST['admin_name'];
            $executed_at = date('Y-m-d H:i:s');

            // Insert data into the database
            $sql = "INSERT INTO admin_management (action_type, admin_name, executed_at) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $admin_action, $admin_name, $executed_at);

            if ($stmt->execute()) {
                echo "<p style='color: green;'>Admin action recorded successfully.</p>";
            } else {
                echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
            }

            $stmt->close();
        }
        ?>
        <form id="admin-form" method="POST">
            <div class="form-group">
                <label for="admin-action">Select Action</label>
                <select id="admin-action" name="admin_action">
                    <option value="add">Add Admin</option>
                    <option value="remove">Remove Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label for="admin-name">Admin Name</label>
                <input type="text" id="admin-name" name="admin_name" placeholder="Enter admin name" required>
            </div>
            <div class="form-group">
                <button type="submit">Submit Action</button>
            </div>
        </form>
        <nav>
            <a href="view_candidates.php">View Candidate Details</a>
            <a href="home.php">Back to home</a>
        </nav>
    </div>
</body>

</html>