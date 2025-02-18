<?php
// Database connection parameters
$servername = "localhost";
$username = "root";  // Your MySQL username
$password = "";  // Your MySQL password
$dbname = "voting";  // Your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch the election results
$sql = "
    SELECT c.full_name AS full_name, COUNT(v.user_id) AS votes 
    FROM vote v
    INNER JOIN candidate c ON v.candidate_id = c.candidate_id
    GROUP BY v.candidate_id
    ORDER BY votes DESC
";
$result = $conn->query($sql);

// Prepare the result array
$results = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }
} else {
    $results[] = ['full_name' => 'No data', 'votes' => 0];
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Results</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, #eef2f3, #dfe6ee);
            color: #333;
        }

        /* Header */
        header {
            background: linear-gradient(90deg, #004aad, #007bff);
            color: white;
            text-align: center;
            padding: 1.5rem 0;
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.2);
        }

        header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: bold;
        }

        /* Navigation */
        nav ul {
            list-style: none;
            padding: 0;
            margin: 15px 0 0 0;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 20px;
            background: linear-gradient(to right, #004aad, #007bff);
            transition: all 0.3s ease;
        }

        nav a:hover {
            background: linear-gradient(to right, #003080, #0056b3);
            transform: scale(1.1);
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Main Content */
        main {
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 700px;
            width: 100%;
            animation: fadeIn 1.2s ease-in-out;
            text-align: center;
        }

        h2 {
            color: #004aad;
            margin-bottom: 20px;
            font-size: 2rem;
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

        #results-display {
            margin-top: 20px;
            padding: 20px;
            background: #f8f9fc;
            border-radius: 12px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            font-size: 1rem;
            line-height: 1.6;
        }

        #results-display h3 {
            color: #004aad;
            margin-bottom: 10px;
            font-size: 1.5rem;
        }

        #results-display ul {
            list-style: none;
            padding: 0;
        }

        #results-display li {
            background: #e6ecf5;
            padding: 12px;
            margin: 8px 0;
            border-radius: 8px;
            box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1);
            font-size: 1.1rem;
            font-weight: 500;
        }

        #results-display li:nth-child(odd) {
            background: #dfe6ee;
        }

        /* Footer */
        footer {
            background: linear-gradient(90deg, #004aad, #007bff);
            color: white;
            text-align: center;
            padding: 1.5rem 0;
            font-size: 0.9rem;
            margin-top: 30px;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header>
        <h1>Get Results</h1>
        <nav>
            <ul>
                <li><a href="admin_register_candidate.php">Register Candidate</a></li>
                <li><a href="admin_vote_viewer.php">View Voting Process</a></li>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <section class="section">
            <h2>View Election Results</h2>
            <button id="fetch-results">Fetch Results</button>
            <div id="results-display">
                <p>Click "Fetch Results" to see the election results.</p>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Admin Dashboard | All Rights Reserved</p>
    </footer>

    <script>
        document.getElementById('fetch-results').addEventListener('click', function () {
            const resultsDisplay = document.getElementById('results-display');
            resultsDisplay.innerHTML = `
                <p>Loading results, please wait...</p>
            `;

            // Convert PHP data into a JavaScript object
            const results = <?php echo json_encode($results); ?>;

            // Update the UI with the fetched results
            resultsDisplay.innerHTML = `
                <h3>Election Results</h3>
                <ul>
                    ${results.map(result => `<li>${result.candidate_name}: ${result.votes} votes</li>`).join('')}
                </ul>
            `;
        });
    </script>
</body>

</html>
