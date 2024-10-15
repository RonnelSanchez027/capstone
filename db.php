<?php
$host = 'localhost:3307';
$user = 'publ_consultation'; // Default XAMPP username
$pass = 'N2e%iPhrbuKesz%H'; // Default XAMPP password (leave blank)
$dbname = 'publ_admin_db'; // Name of your database

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
