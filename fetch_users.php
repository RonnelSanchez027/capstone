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

// Step 1: Sync new users from the users table to modified_users
$sync_sql = "
    INSERT INTO modified_users (username, email)
    SELECT username, email FROM users
    WHERE NOT EXISTS (
        SELECT 1 FROM modified_users WHERE modified_users.username = users.username
    )
";

if ($conn->query($sync_sql) === FALSE) {
    die("Error syncing users: " . $conn->error);
}

// Step 2: Cleanup deleted users in modified_users
$cleanup_sql = "
    DELETE FROM modified_users
    WHERE username NOT IN (
        SELECT username FROM users
    )
";

if ($conn->query($cleanup_sql) === FALSE) {
    die("Error cleaning up modified_users: " . $conn->error);
}

// Step 3: Fetch users to display or further process
$sql = "SELECT * FROM modified_users";
$result = $conn->query($sql);

if ($result === FALSE) {
    die("Error: " . $conn->error);
}

if ($result->num_rows > 0) {
    // Display the results or process them further
    while ($row = $result->fetch_assoc()) {
        echo "Username: " . $row['username'] . ", Email: " . $row['email'] . "<br>";
    }
} else {
    echo "No users found in the modified_users table.";
}

$conn->close();
?>
