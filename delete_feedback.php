<?php
// Database connection
$connection = mysqli_connect("localhost:3307", "root", "", "admin_db");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    // Sanitize inputs
    $id = mysqli_real_escape_string($connection, $id);

    // Delete feedback from feedbacks table
    $deleteFeedbackQuery = "DELETE FROM feedbacks WHERE id='$id'";
    
    if (mysqli_query($connection, $deleteFeedbackQuery)) {
        header("Location: temp2.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}
?>
