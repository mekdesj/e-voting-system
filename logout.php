<?php
session_start(); // Start the session

// Destroy the session to log out the user
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to the homepage or login page
header("Location: home.php"); // Or you can redirect to login page (e.g., "login.php")
exit;
?>
