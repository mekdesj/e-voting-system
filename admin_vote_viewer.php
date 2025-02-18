<?php
// Include your existing database connection file
include('db_connection.php');

// Query to fetch student details along with their voting status by checking if the user_id exists in the vote table
$sql = "SELECT student.user_id AS id, student.full_name AS name, 
               IF(vote.user_id IS NOT NULL, 1, 0) AS vote_status
        FROM student 
        LEFT JOIN vote ON student.user_id = vote.user_id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Voting Process</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        header,
        footer {
            background-color: #004aad;
            color: #fff;
            padding: 1rem 0;
            text-align: center;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: bold;
        }

        /* Navigation Styles */
        .nav-links {
            list-style-type: none;
            padding: 0;
            margin: 10px 0 0 0;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .nav-links li {
            margin: 0;
        }

        .nav-links a {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            padding: 10px 15px;
            border-radius: 20px;
            background: linear-gradient(to right, #004aad, #007bff);
            transition: all 0.3s ease;
        }

        .nav-links a:hover {
            background: linear-gradient(to right, #003080, #0056b3);
            transform: scale(1.05);
        }

        /* Main Content */
        main {
            padding: 40px 20px;
        }

        .section {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
            animation: fadeIn 1s ease-in-out;
        }

        h2 {
            color: #004aad;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #004aad;
            color: white;
        }

        .status {
            font-weight: bold;
            color: green;
        }

        .status.no-vote {
            color: red;
        }

        /* Footer */
        footer p {
            margin: 0;
            font-size: 0.9rem;
        }

        /* Animation */
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
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <h1>Voting Process</h1>
        <nav>
            <ul class="nav-links">
                <li><a href="admin_register_candidate.php">Register Candidate</a></li>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="admin_result.php">Get Results</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <section class="section">
            <h2>View Voting Process</h2>
            <div id="voting-process">
                <?php if ($result->num_rows > 0) { ?>
                    <table>
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Voting Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Loop through the rows and display student info and their voting status
                            while ($row = $result->fetch_assoc()) {
                                $status = ($row['vote_status'] == 1) ? 
                                          "<span class='status'>✅ Voted</span>" : 
                                          "<span class='status no-vote'>❌ Not Voted</span>";
                                echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['name']}</td>
                                        <td>$status</td>
                                      </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <p>No users found.</p>
                <?php } ?>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 Admin Dashboard</p>
    </footer>

</body>

</html>

<?php
// Close the database connection
$conn->close();
?>
