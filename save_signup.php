<?php
session_start();
include 'db.php'; // Include database connection

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve credentials from the session
    $username = $_SESSION['signup_data']['username'];
    $password = $_SESSION['signup_data']['password'];
    $email = $_SESSION['signup_data']['email'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute the SQL query to insert user data
    $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $email);

    if ($stmt->execute()) {
        // Clear the session data
        unset($_SESSION['signup_data']);
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    $stmt->close();
}
$conn->close();
?>
