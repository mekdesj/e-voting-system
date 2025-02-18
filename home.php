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

// Fetch the election start and end dates from the database
$sql = "SELECT start_datetime, end_datetime FROM elections ORDER BY id DESC LIMIT 1"; 
$result = $conn->query($sql);

$start_datetime = "";
$end_datetime = "";

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $start_datetime = $row['start_datetime'];
    $end_datetime = $row['end_datetime'];
} else {
    echo "No elections found.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DDU Student Union Voting System</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            line-height: 1.6;
            overflow-x: hidden;
        }

        header {
            background-image: url("images/epic.png");
            color: #fff;
            text-align: center;
            padding: 2rem 0;
            font-size: 2.5rem;
            font-weight: bold;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        nav {
            position: sticky;
            top: 0;
            background-color: #004aad;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            z-index: 100;
        }

        nav ul {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 1rem 0;
        }

        nav ul li {
            margin: 0 20px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 1rem;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        nav ul li a:hover {
            color: #f9c74f;
        }

        .hero {
            background: linear-gradient(45deg, #004aad, #6fb1fc);
            color: #fff;
            text-align: center;
            padding: 100px 20px;
            background-image: url('images/elit.png');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
        }

        .hero-content {
            position: relative;
            z-index: 1;
            animation: fadeIn 2s ease-in-out;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 20px;
        }

        .btn-container {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .btn {
            display: inline-block;
            padding: 15px 40px;
            font-size: 1.2rem;
            color: #fff;
            background-color: #4f3803;
            text-decoration: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            background-color: #842e03;
            transform: translateY(-5px);
        }

        .countdown {
            font-size: 2rem;
            margin-top: 20px;
            color: #fff;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        footer {
            background-color: #004aad;
            color: #fff;
            text-align: center;
            padding: 1.5rem 0;
            margin-top: 50px;
        }

        footer p {
            font-size: 1rem;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            .btn {
                font-size: 1rem;
                padding: 10px 20px;
            }

            .hero p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        Dire Dawa University Student Union Voting System
    </header>

    <!-- Navigation -->
    <nav>
        <ul>
            <li><a href="home.html">Home</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="help.html">Help</a></li>
            <li><a href="contact.html">Contact</a></li>
        </ul>
    </nav>
    
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to the Online Voting System</h1>
            <p>Cast your vote securely and easily for the Dire Dawa University Student Union elections.</p>
            <div class="btn-container">
                <a href="login.html" class="btn">Login</a>
                <a href="registration.php" class="btn">Register</a>
            </div>

            <!-- Display voting time -->
            <p class="countdown">Voting starts at: <span id="start-time"><?= $start_datetime ?></span></p>
            <p class="countdown">Voting ends at: <span id="end-time"><?= $end_datetime ?></span></p>

            <p class="countdown">Voting closes in: <span id="countdown-timer"></span></p>
        </div>
    </section>
    
    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Dire Dawa University Student Union</p>
    </footer>

    <!-- Countdown Timer Script -->
    <script>
        const countdownElement = document.getElementById('countdown-timer');
        const endTime = new Date("<?= $end_datetime ?>");  // Using the PHP value for the end time

        function updateCountdown() {
            const now = new Date();
            const timeDifference = endTime - now;

            if (timeDifference <= 0) {
                countdownElement.textContent = 'Voting has ended';
                return;
            }

            const days = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeDifference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);

            countdownElement.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
        }

        setInterval(updateCountdown, 1000);
    </script>

</body>
</html>
