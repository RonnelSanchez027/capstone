<?php
$servername = "localhost:3307";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "admin_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT age_group, satisfaction, comments, created_at FROM feedback ORDER BY created_at DESC";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='post'>";
        echo "<strong>Age Group:</strong> " . $row["age_group"] . "<br>";
        echo "<strong>Satisfaction:</strong> " . $row["satisfaction"] . "<br>";
        echo "<strong>Comments:</strong> " . $row["comments"] . "<br>";
        echo "<small>Submitted on: " . $row["created_at"] . "</small>";
        echo "</div>";
    }
} else {
    echo "No feedback submitted yet.";
}

$conn->close();
?>