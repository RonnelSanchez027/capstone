<?php
// Database connection
$connection = mysqli_connect("localhost:3307", "root", "", "admin_db");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $feedback = $_POST['feedback'];
    $rating = $_POST['rating'];

    // Sanitize inputs
    $id = mysqli_real_escape_string($connection, $id);
    $feedback = mysqli_real_escape_string($connection, $feedback);
    $rating = mysqli_real_escape_string($connection, $rating);

    // Update feedback in feedbacks table
    $updateFeedbackQuery = "UPDATE feedbacks SET feedback='$feedback', rating='$rating' WHERE id='$id'";
    
    if (mysqli_query($connection, $updateFeedbackQuery)) {
        header("Location: temp2.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}
?>

