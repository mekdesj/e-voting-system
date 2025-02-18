<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e6f0ff;
        }

        header {
            background-color: #0056b3;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
        }

        nav ul li {
            margin: 0 10px;
        }

        nav ul li a {
            text-decoration: none;
            color: #fff;
            padding: 5px 10px;
            border-radius: 4px;
        }

        nav ul li a:hover {
            background-color: #003d80;
            transition: background-color 0.3s ease-in-out;
        }

        main {
            padding: 20px;
        }

        .card {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 20px auto;
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
    </style>
</head>

<body>
    <!-- Header -->
    <header>
        <h1>Admin Dashboard</h1>
        <nav>
            <ul>
                <li><a href="home.php">home</a></li>
                <li><a href="create_election.php">Create Election</a></li>
                <li><a href="admin_register_candidate.php">Register Candidate</a></li>
                <li><a href="admin_result.php">Get Results</a></li>
                <li><a href="admin_vote_viewer.php">View Voting Process</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <div class="card">
            <h2>Welcome, Admin!</h2>
            <p>Use the navigation menu to manage the election process. Start by creating an election if none exists.</p>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Admin Dashboard | All Rights Reserved</p>
    </footer>
</body>

</html>
