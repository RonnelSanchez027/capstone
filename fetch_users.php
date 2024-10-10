<?php
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "admin_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully<br>";

// Fetch users from the users table
$sql = "SELECT id, username, email FROM users";
$result = $conn->query($sql);

if ($result === FALSE) {
    die("Error: " . $conn->error);
}

if ($result->num_rows > 0) {
    // Clear existing data in modified_users
    $conn->query("TRUNCATE TABLE modified_users"); 

    while ($row = $result->fetch_assoc()) {
        $insert_sql = "INSERT INTO modified_users (id, username, email, full_name) VALUES (" . 
                      $row['id'] . ", '" . 
                      $row['username'] . "', '" . 
                      $row['email'] . "', NULL)"; 

        if ($conn->query($insert_sql) === FALSE) {
            die("Error inserting user: " . $conn->error);
        }
    }
    echo "Users fetched and added to modified_users table!";
} else {
    echo "No users found in the users table.";
}

$conn->close();
?>
