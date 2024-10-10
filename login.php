<?php
session_start();
include 'db.php'; // Include database connection

// Check if the signup was successful
$signup_success = isset($_SESSION['signup_success']) ? $_SESSION['signup_success'] : false;
if ($signup_success) {
    // Clear the session variable
    unset($_SESSION['signup_success']);
}

// Process login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if both username and password are provided
    if (empty($_POST['username']) || empty($_POST['password'])) {
        echo "Please enter both username and password.";
        exit;
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user was found
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify password if user exists
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id']; // Store user ID in session
            $_SESSION['username'] = $user['username']; // Store username in session
            header("Location: index.php"); // Redirect to dashboard
            exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "User not found.";
    }

    $stmt->close();
}

$conn->close();
?>
