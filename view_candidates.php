
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Candidate Details</title>
    <style>
        /* Add your styles here */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }

        .container {
            max-width: 800px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #0862bd;
            color: white;
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
        <h1>Candidate Details</h1>
        <table>
            <thead>
                <tr>
                    <th>Candidate ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>candidate_details</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Database connection
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

                // Fetch candidates from the database
                $sql = "SELECT candidate_id, full_name, username, candidate_details FROM candidate";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['candidate_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['candidate_details']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No candidates found</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
        <nav>
            <a href="manage_admin.php">Manage Admin Activities</a>
        </nav>
    </div>
</body>

</html>