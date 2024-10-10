<?php
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "admin_db";

// Create connection
$connection = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Handle fetching the specific comment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $appointmentId = $_POST['id'];

    // Fetch the comment related to the appointment
    $sql = "SELECT comment FROM comments WHERE appointment_id = ? LIMIT 1"; // Get one comment
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $appointmentId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Output the specific comment or a message if none exists
    if ($row = $result->fetch_assoc()) {
        echo htmlspecialchars($row['comment']); // Only output the comment
    } else {
        echo "No comments available.";
    }

    $stmt->close();
    exit; // Stop further script execution
}

// Close connection
$connection->close();
?>
