<?php
session_start();
include 'db.php'; // Include your database connection

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // Generate a random 6-digit verification code
    $verification_code = mt_rand(100000, 999999);

    // Save the code temporarily in session or database (optional)
    $_SESSION['verification_code'] = $verification_code;

    // Define the subject of the email
    $subject = "Verification Code for LGU-1 Public Consultation";

    // Create the HTML content of the email
    $message = "
    <html>
    <head>
        <title>Verification Code for LGU-1 Public Consultation</title>
    </head>
    <body>
        <p>Dear User,</p>
        <p>Thank you for signing up for the LGU-1 Public Consultation platform. Please use the verification code below to complete your registration:</p>
        <p><strong>Verification Code: <span style='font-size: 24px; color: #007bff;'>$verification_code</span></strong></p>
        <p>Enter the above code on the registration page to confirm your email and proceed with your account creation.</p>
        <p>If you didn't request this, please ignore this message.</p>
        <p>Thank you,<br>LGU-1 Public Consultation Team</p>
        <footer>
            <p><small>This is an automated message. Please do not reply to this email.</small></p>
        </footer>
    </body>
    </html>
    ";

    // Set the headers for the email (indicating that it's HTML content)
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: no-reply@lgu-1.com" . "\r\n"; // Replace with your actual 'from' email address

    // Send the email using PHP's mail() function
    if (mail($email, $subject, $message, $headers)) {
        echo 'success'; // If email is sent successfully, respond with success
    } else {
        echo 'error'; // In case of failure to send the email
    }
} else {
    echo 'no_email'; // If the email was not sent
}
?>
