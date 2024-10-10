<?php
// Database connection
$connection = mysqli_connect("localhost:3307", "root", "", "admin_db");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointment_id = $_POST['appointment_id'];
    $comment = $_POST['comment'];

    // Sanitize inputs
    $appointment_id = mysqli_real_escape_string($connection, $appointment_id);
    $comment = mysqli_real_escape_string($connection, $comment);

    // Insert comment into comments table
    $insertCommentQuery = "INSERT INTO comments (appointment_id, comment) VALUES ('$appointment_id', '$comment')";
    mysqli_query($connection, $insertCommentQuery);

    // Redirect back to the consultation page
    header("Location: consultation.php");
    exit();
}
?>
