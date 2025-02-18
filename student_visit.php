<?php
// Disable error reporting for production
ini_set('display_errors', 1); // Set to 0 in production, set to 1 for debugging
error_reporting(E_ALL);

session_start(); // Start the session

include('db_connection.php');

// Get the user_id from session
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Replace with a dynamic user ID in real scenarios

// Check if user is logged in
if (!$user_id) {
    $_SESSION['message'] = 'You must log in to vote.';
    $_SESSION['message_type'] = 'error';
    header("Location: login.php"); // Redirect to login page if user is not logged in
    exit;
}

// SQL to fetch candidates
$sql = "SELECT candidate_id, full_name, department, campaign, candidate_picture FROM candidate";
$result = $conn->query($sql);

// Handle voting logic
if (isset($_POST['vote'])) {
    $candidate_id = $_POST['candidate_id'];

    // Check if the user exists in the student table
    $check_user_sql = "SELECT * FROM student WHERE user_id = $user_id";
    $check_user_result = $conn->query($check_user_sql);

    if ($check_user_result->num_rows == 0) {
        $_SESSION['message'] = 'Error: User does not exist in the system.';
        $_SESSION['message_type'] = 'error';
    } else {
        // Check if the user has already voted
        $check_vote_sql = "SELECT * FROM vote WHERE user_id = $user_id";
        $check_vote_result = $conn->query($check_vote_sql);

        if ($check_vote_result->num_rows > 0) {
            $_SESSION['message'] = 'You have already voted.';
            $_SESSION['message_type'] = 'warning';
        } else {
            // Insert the vote into the vote table
            $insert_vote_sql = "INSERT INTO vote (user_id, candidate_id) VALUES ($user_id, $candidate_id)";
            if ($conn->query($insert_vote_sql) === TRUE) {
                $_SESSION['message'] = 'Vote successfully submitted!';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Error: ' . $conn->error;
                $_SESSION['message_type'] = 'error';
            }
        }
    }

    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Voting Page</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
        }

        header {
            background-color: #004aad;
            color: white;
            text-align: center;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        nav ul {
            display: flex;
            justify-content: center;
            list-style: none;
            background-color: #004aad;
            margin: 0;
            padding: 10px;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        nav ul li a:hover {
            text-decoration: underline;
        }

        /* Flash Message */
        .flash-message {
            text-align: center;
            padding: 15px;
            margin: 20px auto;
            max-width: 600px;
            border-radius: 5px;
            font-size: 1rem;
        }

        .flash-message.warning {
            background-color: #ffcc00;
            color: #333;
        }

        .flash-message.success {
            background-color: #28a745;
            color: white;
        }

        .flash-message.error {
            background-color: #dc3545;
            color: white;
        }

        /* Candidate Section */
        .candidates-section {
            text-align: center;
            padding: 50px 20px;
        }

        .candidates-section h2 {
            font-size: 2rem;
            margin-bottom: 30px;
            color: #004aad;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
        }

        .candidates-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 0 20px;
        }

        .candidate-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeIn 0.8s ease-in-out;
        }

        .candidate-card:hover {
            transform: translateY(-10px);
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
        }

        .candidate-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 15px;
        }

        .vote-btn {
            border: none;
            cursor: pointer;
            background-color: #007bff; /* Blue background color */
            color: white; /* White text */
            padding: 12px 25px; /* Adjusted padding for better button size */
            border-radius: 50px; /* Rounded corners */
            font-size: 1rem; /* Font size */
            transition: background-color 0.3s ease, transform 0.2s ease; /* Smooth transition */
        }

        .vote-btn:hover {
            background-color: #0056b3; /* Darker blue when hovered */
            transform: scale(1.05); /* Slight scale-up effect on hover */
        }

        .vote-btn:active {
            background-color: #004085; /* Even darker blue when clicked */
        }

        /* Footer */
        footer {
            background-color: #004aad;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
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
    <header>
        <h1>Dire Dawa University Student Union Voting System</h1>
    </header>

    <!-- Navigation -->
    <nav>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="help.html">Help</a></li>
            <li><a href="contact.html">Contact</a></li>
        </ul>
    </nav>

    <!-- Flash Message -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="flash-message <?= $_SESSION['message_type'] ?>">
            <?= $_SESSION['message'] ?>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <!-- Candidates Section -->
    <section class="candidates-section">
        <h2>Vote for Your Favorite Candidate</h2>
        <div class="candidates-container">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php
                    $candidate_name = $row['full_name'] ?? 'Unknown';
                    $department = $row['department'] ?? 'Unknown';
                    $campaign = $row['campaign'] ?? 'No campaign available';
                    $photo = !empty($row['candidate_picture']) ? 'uploads/' . $row['candidate_picture'] : 'uploads/default.jpg';
                    $candidate_id = $row['candidate_id'] ?? 0;
                    ?>
                    <div class="candidate-card">
                        <img src="<?= $photo ?>" alt="<?= htmlspecialchars($candidate_name) ?>">
                        <h3><?= $candidate_name ?></h3>
                        <p>Department: <?= $department ?></p>
                        <p>Campaign: <?= $campaign ?></p>
                        <form action="" method="POST">
                            <input type="hidden" name="candidate_id" value="<?= $candidate_id ?>">
                            <button type="submit" name="vote" class="vote-btn">Vote</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No candidates found.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Dire Dawa University Student Union</p>
    </footer>
</body>
</html>

<?php $conn->close(); ?>
