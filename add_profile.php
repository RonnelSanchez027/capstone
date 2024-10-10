<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $job_title = $_POST['job_title'];
    $role = $_POST['role'];
    $status = $_POST['status'];

    // Handle image upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);

    // Here, you would typically insert data into the database.
    // Example SQL: 
    // INSERT INTO users (full_name, job_title, role, status, profile_picture) VALUES ('$full_name', '$job_title', '$role', '$status', '$target_file');

    // Redirect or show success message
    echo "Profile saved successfully!";
}
?>
