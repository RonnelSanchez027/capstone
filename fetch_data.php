<?php
function fetchAnalyticsData() {
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

    // Fetch users
    $userQuery = "SELECT username FROM users";
    $userResult = $conn->query($userQuery);

    $usernames = [];
    while ($row = $userResult->fetch_assoc()) {
        $usernames[] = $row['username'];
    }

    // Fetch appointment count
    $appointmentQuery = "SELECT COUNT(*) as count FROM appointment";
    $appointmentResult = $conn->query($appointmentQuery);
    $appointmentCount = $appointmentResult->fetch_assoc()['count'];

    // Fetch answered comments count
    $commentsQuery = "SELECT COUNT(*) as count FROM comments";
    $commentsResult = $conn->query($commentsQuery);
    $commentsCount = $commentsResult->fetch_assoc()['count'];

    // Fetch scheduled events grouped by month
    $scheduleQuery = "SELECT DATE_FORMAT(date, '%Y-%m') as month, COUNT(*) as count FROM schedule GROUP BY month ORDER BY month";
    $scheduleResult = $conn->query($scheduleQuery);
    
    $scheduleCounts = [];
    $scheduleMonths = [];
    while ($row = $scheduleResult->fetch_assoc()) {
        $scheduleMonths[] = $row['month'];
        $scheduleCounts[] = (int)$row['count'];
    }

    $conn->close();

    return [
        'usernames' => $usernames,
        'appointmentCount' => $appointmentCount,
        'commentsCount' => $commentsCount,
        'scheduleMonths' => $scheduleMonths,
        'scheduleCounts' => $scheduleCounts,
    ];
}
?>
