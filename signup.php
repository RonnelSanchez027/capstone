<?php
session_start(); // Start the session
include 'db.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit;
    }

    // Hash the password after confirming it matches
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $email);

    if ($stmt->execute()) {
        // Set a session variable to indicate successful signup
        $_SESSION['signup_success'] = true; 
        // Redirect to login page after successful registration
        header("Location: login.html"); // Change to login.php if you're using PHP
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
