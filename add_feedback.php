<?php
$connection = mysqli_connect("localhost:3307", "root", "", "admin_db");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointment_id = $_POST['appointment_id'];
    $comment_id = $_POST['comment_id'];
    $feedback = $_POST['feedback'];
    $rating = $_POST['rating'];

    // Sanitize inputs
    $appointment_id = mysqli_real_escape_string($connection, $appointment_id);
    $comment_id = mysqli_real_escape_string($connection, $comment_id);
    $feedback = mysqli_real_escape_string($connection, $feedback);
    $rating = mysqli_real_escape_string($connection, $rating);

    // Insert feedback into feedbacks table
    $insertFeedbackQuery = "INSERT INTO feedbacks (appointment_id, comment_id, feedback, rating) VALUES ('$appointment_id', '$comment_id', '$feedback', '$rating')";
    
    if (mysqli_query($connection, $insertFeedbackQuery)) {
        header("Location: temp2.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}
?>
