<?php
$host = 'localhost:3307';
$user = 'root'; // Default XAMPP username
$pass = ''; // Default XAMPP password (leave blank)
$dbname = 'admin_db'; // Name of your database

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
