<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - DDU Student Union Voting System</title>
    <style>
      /* General Reset */
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
        }

        header {
            background-color: #004aad;
            color: #fff;
            text-align: center;
            padding: 2rem 0;
            font-size: 1.8rem;
            font-weight: bold;
        }

        nav {
            background-color: #004aad;
            display: flex;
            justify-content: center;
            padding: 1rem 0;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        nav ul li {
            margin: 0;
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
            text-align: center;
            background: linear-gradient(45deg, #004aad, #6fb1fc);
            color: #fff;
            padding: 50px 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .hero p {
            font-size: 1.2rem;
        }

        .register-content {
            padding: 2rem;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin: 30px auto;
            max-width: 600px;
        }

        .register-content h2 {
            text-align: center;
            font-size: 1.8rem;
            color: #004aad;
            margin-bottom: 20px;
        }

        .form-container {
            width: 100%;
            padding: 1rem 2rem;
        }

        form label {
            display: block;
            font-weight: bold;
            margin: 15px 0 5px;
        }

        form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        form button {
            display: block;
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            color: #fff;
            background-color: #004aad;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        form button:hover {
            background-color: #003080;
            transform: scale(1.05);
        }

        footer {
            background-color: #004aad;
            color: #fff;
            text-align: center;
            padding: 1.5rem 0;
            margin-top: 30px;
        }  /* Styles omitted for brevity (reuse your provided styles) */
    </style>
</head>
<body>

<header>
    Dire Dawa University Student Union Voting System
</header>

<nav>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="about.html">About</a></li>
        <li><a href="help.html">Help</a></li>
        <li><a href="contact.html">Contact</a></li>
    </ul>
</nav>

<section class="hero">
    <h1>Create Your Account</h1>
    <p>Fill in the form below to register and participate in the voting process.</p>
</section>

<section class="register-content">
    <h2>Register Now</h2>
    <div class="form-container">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" onsubmit="return validateForm()">
    <label for="full-name">Full Name</label>
    <input type="text" id="full-name" name="full_name" placeholder="Enter your full name" required pattern="[A-Za-z\s]+">

    <label for="user-id">User ID</label>
    <input type="text" id="user-id" name="user_id" placeholder="Enter your User ID" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" placeholder="Enter your email" required>

    <label for="password">Password</label>
    <input type="password" id="password" name="password" placeholder="Enter a secure password" required>

    <label for="confirm-password">Confirm Password</label>
    <input type="password" id="confirm-password" name="confirm_password" placeholder="Re-enter your password" required>

    <button type="submit">Register</button>
</form>


        <script>
            function validateForm() {
                const password = document.getElementById("password").value;
                const confirmPassword = document.getElementById("confirm-password").value;

                if (password !== confirmPassword) {
                    alert("Passwords do not match!");
                    return false;
                }
                return true;
            }
        </script>
    </div>
</section>

<footer>
    <p>&copy; 2024 Dire Dawa University Student Union</p>
</footer>

<?php
// Database configuration
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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = trim($_POST['full_name']);
    $user_id = trim($_POST['user_id']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate required fields
    if (empty($full_name) || empty($user_id) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit();
    }
    

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check if user_id already exists
    $stmt = $conn->prepare("SELECT * FROM student WHERE user_id = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('User ID already exists. Please choose another.'); window.history.back();</script>";
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO student (user_id, full_name, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $user_id, $full_name, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful!'); window.location.href = 'home.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
        }
    }

    $stmt->close();
}

$conn->close();
?>

</body>
</html>