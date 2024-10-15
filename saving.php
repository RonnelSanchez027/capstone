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
        $error_message = "Passwords do not match!";
    } else {
        // Check password strength
        $strength = checkPasswordStrength($password);
        if ($strength['level'] === 'weak') {
            $error_message = "Password is too weak! Please choose a stronger password.";
        } elseif ($strength['level'] === 'strong') {
            $_SESSION['signup_data'] = [
                'username' => $_POST['username'],
                'password' => $password,
                'email' => $_POST['email'],
            ];
            $_SESSION['show_modal'] = true; // Show terms modal
            header("Location: saving.php");
            exit();
        } elseif ($strength['level'] === 'medium') {
            $_SESSION['signup_data'] = [
                'username' => $_POST['username'],
                'password' => $password,
                'email' => $_POST['email'],
            ];
            $_SESSION['show_medium_modal'] = true; // Indicate to show medium password modal
            header("Location: saving.php");
            exit();
        }
    }
}

$conn->close();

function checkPasswordStrength($password) {
    if (strlen($password) < 8) {
        return ['level' => 'weak', 'text' => 'Weak Password', 'class' => 'weak'];
    }
    if (preg_match('/[A-Z]/', $password) && preg_match('/\d/', $password) && preg_match('/[!@#$%^&*]/', $password)) {
        return ['level' => 'strong', 'text' => 'Strong Password', 'class' => 'strong'];
    }
    return ['level' => 'medium', 'text' => 'Medium Password', 'class' => 'medium'];
}
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
        .password-strength {
            margin-top: 10px;
            font-weight: bold;
        }
        .weak { color: red; }
        .medium { color: orange; }
        .strong { color: green; }
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            .image-section {
                display: none; /* Hide the image section on mobile */
            }
            .form-section {
                padding: 20px;
                background-color: rgba(255, 255, 255, 0.9);
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

        <form action="saving.php" method="post" id="signupForm">
            <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="form-group position-relative">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <span class="toggle-password" toggle="#password"></span>
                <div id="passwordStrength" class="password-strength"></div>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block mt-3">Sign Up</button>
        </form>
        
        <p class="mt-3">Already have an account? <a href="login.php">Login here</a></p>
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
            <div id="error-message" class="error" style="display:none; padding: 10px; background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 0.25rem;"></div>
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
                <div class="mt-3">
                    <h6>Terms and Conditions</h6>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="checkAll">
                        <label class="form-check-label" for="checkAll">Check all</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="check1">
                        <label class="form-check-label" for="check1">I acknowledge and agree to all of the following.</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="check2">
                        <label class="form-check-label" for="check2">I agree to Terms of Service</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="check3">
                        <label class="form-check-label" for="check3">I agree to Privacy Police</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Decline</button>
                <button type="button" class="btn btn-primary btn-block" id="acceptTerms">Accept</button>
            </div>
        </div>
    </div>
</div>

<!-- Medium Password Warning Modal -->
<div class="modal fade" id="mediumPasswordModal" tabindex="-1" role="dialog" aria-labelledby="mediumPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumPasswordModalLabel">Password Warning</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Your password is considered medium strength. While you can proceed, it is recommended to use a stronger password for better security.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Go Back</button>
                <button type="button" class="btn btn-primary btn-block" id="proceedBtn">Proceed</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        // Show modals based on session variable
        <?php if (isset($_SESSION['show_modal'])): ?>
            $('#termsModal').modal('show');
            <?php unset($_SESSION['show_modal']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['show_medium_modal'])): ?>
            $('#mediumPasswordModal').modal('show');
            <?php unset($_SESSION['show_medium_modal']); ?>
        <?php endif; ?>

        const passwordField = document.getElementById('password');
        const passwordStrength = document.getElementById('passwordStrength');

        passwordField.addEventListener('input', () => {
            const password = passwordField.value;
            const strength = checkPasswordStrength(password);
            passwordStrength.innerText = strength.text;
            passwordStrength.className = `password-strength ${strength.class}`;
        });

        function checkPasswordStrength(password) {
            if (password.length < 8) {
                return { level: 'weak', text: 'Weak Password', class: 'weak' };
            }
            if (/[A-Z]/.test(password) && /\d/.test(password) && /[!@#$%^&*]/.test(password)) {
                return { level: 'strong', text: 'Strong Password', class: 'strong' };
            }
            return { level: 'medium', text: 'Medium Password', class: 'medium' };
        }

        // Checkbox functionality
        const checkAll = document.getElementById('checkAll');
        const checkboxes = [document.getElementById('check1'), document.getElementById('check2'), document.getElementById('check3')];

        checkAll.addEventListener('change', function() {
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked === false) {
                    checkAll.checked = false;
                }
            });
        });

        // Proceed button logic
        document.getElementById('proceedBtn').onclick = function() {
            const errorMessageDiv = document.getElementById('error-message');
            errorMessageDiv.style.display = 'none'; // Hide error message initially

            const allChecked = checkboxes.every(checkbox => checkbox.checked);
            if (!allChecked) {
                errorMessageDiv.innerText = "You must agree to all terms and conditions.";
                errorMessageDiv.style.display = 'block'; // Show error message
                return; // Stop the process
            }

            $('#mediumPasswordModal').modal('hide');
            $('#termsModal').modal('show'); // Show terms modal again
        };

        // Accept button logic
        document.getElementById('acceptTerms').onclick = function() {
            const errorMessageDiv = document.getElementById('error-message');
            errorMessageDiv.style.display = 'none'; // Hide error message

            const allChecked = checkboxes.every(checkbox => checkbox.checked);
            if (!allChecked) {
                errorMessageDiv.innerText = "You must agree to all terms and conditions.";
                errorMessageDiv.style.display = 'block'; // Show error message
                return; // Stop the process
            }

            const formData = new FormData(document.getElementById('signupForm'));
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
        };
    });
</script>


</body>
</html>
