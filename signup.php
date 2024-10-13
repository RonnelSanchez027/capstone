<?php
session_start();
include 'db.php';

// Initialize variables for error messages
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get password and confirm password from POST request
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if the passwords match
    if ($password !== $confirm_password) {
        // If passwords don't match, show an error
        $error_message = "Passwords do not match!";
    } else {
        // Store the submitted data in session for later use
        $_SESSION['signup_data'] = [
            'username' => $_POST['username'],
            'password' => $password,
            'email' => $_POST['email'],
            'confirm_password' => $confirm_password
        ];

        // Show modal for terms and conditions
        $_SESSION['show_modal'] = true;
        header("Location: signup.php");
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Sign Up</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .container {
            display: flex;
            width: 80%;
            max-width: 1000px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            position: relative;
        }
        .image-section {
            flex: 60%;
            background-image: url('pc1.jpg');
            background-size: cover;
            background-position: center;
        }
        .form-section {
            flex: 40%;
            padding: 40px;
            background: white;
            position: relative;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            .image-section {
                display: none; /* Hide the image section on mobile */
            }
            .form-section {
                padding: 20px;
                background-color: rgba(255, 255, 255, 0.9); /* Slightly transparent */
            }
            body {
                background-image: url('pc1.jpg');
                background-size: cover;
                background-position: center;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="image-section"></div>
    <div class="form-section">
        <h2>Create Account</h2>

        <!-- Display error message if passwords do not match -->
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form action="signup.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="form-group position-relative">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <span class="toggle-password" toggle="#password"></span>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block mt-3">Sign Up</button>
        </form>
        <p class="mt-3">Already have an account? <a href="login.html">Login here</a></p>
    </div>
</div>

<!-- Modal for Terms and Conditions -->
<div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel" style="text-align:center">Terms of Service and Privacy Policy</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-height: 300px; overflow-y: auto;">
            <p><strong>Acceptance of Terms</strong></p>
<p>By using this public consultation website, you agree to these Terms of Service. If you do not agree, please do not use the site.</p>

<p><strong>Purpose of the Website</strong></p>
<p>This website is designed to facilitate community engagement by allowing users to provide feedback on local government initiatives, policies, and programs.</p>

<p><strong>User Accounts</strong></p>
<p>You may need to create an account to participate in certain consultations. You are responsible for maintaining the confidentiality of your account details and for all activities that occur under your account.</p>

<p><strong>Public Contributions</strong></p>
<p>By submitting comments or feedback, you grant the local government the right to use and publish your contributions. Please ensure your submissions are respectful and relevant.</p>

<p><strong>User Conduct</strong></p>
<p>You agree to engage in constructive discussions and refrain from harassment, hate speech, or disruptive behavior. Violations may result in removal from the site.</p>

<p><strong>Limitation of Liability</strong></p>
<p>The local government is not liable for any damages resulting from your use of this website or reliance on its content.</p>

<h4><strong>Privacy Policy</strong></h4>

<p><strong>Commitment to Privacy</strong></p>
<p>We are committed to protecting your privacy. This policy outlines how we collect, use, and protect your personal information.</p>

<p><strong>Data Collection</strong></p>
<p>We may collect personal information such as your name and email address when you participate in consultations. We also collect non-personal data like IP addresses for site usage analysis.</p>

<p><strong>Usage of Data</strong></p>
<p>Your personal information may be used to communicate with you about consultations and to improve our services. We do not sell your personal data to third parties.</p>

<p><strong>User Rights</strong></p>
<p>You have the right to access, correct, or delete your personal information. If you wish to exercise these rights, please contact us through the website.</p>

<p><strong>Security Measures</strong></p>
<p>We implement reasonable security measures to protect your information. However, no method of transmission over the internet is completely secure.</p>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="term1">
                    <label class="form-check-label" for="term1">I acknowledge and agree to all of the following.</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="term2" disabled>
                    <label class="form-check-label" for="term2">I agree to <b>Terms of Service</b></label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="term3" disabled>
                    <label class="form-check-label" for="term3">I agree to <b>Privacy Policy</b></label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary flex-fill" data-dismiss="modal">Decline</button>
                <button type="button" class="btn btn-primary flex-fill" id="acceptTerms">Accept</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        <?php if (isset($_SESSION['show_modal'])): ?>
            $('#termsModal').modal('show');
            <?php unset($_SESSION['show_modal']); ?> // Clear the session variable
        <?php endif; ?>

        // Disable checkboxes initially
        document.getElementById('term2').disabled = true;
        document.getElementById('term3').disabled = true;
        document.getElementById('term1').disabled = true; // Disable Term 1 initially

        // Scroll event listener
        document.querySelector('.modal-body').onscroll = function() {
            const modalBody = this;
            const scrollTop = modalBody.scrollTop;
            const scrollHeight = modalBody.scrollHeight;
            const clientHeight = modalBody.clientHeight;

            // Check if scrolled to the bottom
            if (scrollTop + clientHeight >= scrollHeight - 10) {
                // Enable Term 1 when scrolled to the bottom
                document.getElementById('term1').disabled = false;
                // Enable checkboxes when scrolled to the bottom
                document.getElementById('term2').disabled = false;
                document.getElementById('term3').disabled = false;
            }
        };

        // Automatically check Term 2 and Term 3 when Term 1 is checked
        document.getElementById('term1').onclick = function() {
            const term2 = document.getElementById('term2');
            const term3 = document.getElementById('term3');

            if (this.checked) {
                term2.checked = true;  // Check Term 2
                term3.checked = true;  // Check Term 3
            } else {
                term2.checked = false; // Uncheck Term 2
                term3.checked = false; // Uncheck Term 3
            }
        };

        // Accept button logic
        document.getElementById('acceptTerms').onclick = function() {
            const term1 = document.getElementById('term1').checked;
            const term2 = document.getElementById('term2').checked;
            const term3 = document.getElementById('term3').checked;

            if (term1 && term2 && term3) {
                // Save credentials and redirect to login page
                const formData = new FormData();
                formData.append('username', '<?php echo $_SESSION["signup_data"]["username"]; ?>');
                formData.append('password', '<?php echo $_SESSION["signup_data"]["password"]; ?>');
                formData.append('email', '<?php echo $_SESSION["signup_data"]["email"]; ?>');

                fetch('save_signup.php', {
                    method: 'POST',
                    body: formData
                }).then(response => {
                    if (response.ok) {
                        window.location.href = 'login.php'; // Redirect on success
                    } else {
                        alert('Error saving data.');
                    }
                });
            } else {
                alert('Please accept all terms to proceed.');
            }
        };
    });
    
</script>

</body>
</html>
