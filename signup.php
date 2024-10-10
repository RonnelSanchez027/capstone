<?php
session_start(); // Start the session
include 'db.php'; // Include database connection

// Initialize variables for error messages
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Store the submitted data in session for later use
    $_SESSION['signup_data'] = [
        'username' => $_POST['username'],
        'password' => $_POST['password'],
        'email' => $_POST['email'],
        'confirm_password' => $_POST['confirm_password']
    ];

    // Show modal for terms and conditions
    $_SESSION['show_modal'] = true; // Set a session variable
    header("Location: signup.php"); // Redirect to the same page
    exit();
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
        <form action="signup.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="form-group position-relative">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <span class="toggle-password" toggle="#password">
                    <i class="fas fa-eye-slash" id="toggle-icon"></i>
                </span>
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
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-height: 300px; overflow-y: auto;">
                <h1>Terms and Conditions for Public Consultation from Philippines</h1>
<p><strong>Effective Date:</strong> [Insert Date]</p>

<p>Welcome to the Public Consultation from Philippines webpage. By accessing or participating in our consultations, you agree to comply with and be bound by the following terms and conditions.</p>

<h2>1. Purpose of Consultation</h2>
<p>The Public Consultation platform aims to gather input from citizens regarding local government initiatives, policies, and projects. We encourage active participation to ensure that community voices are heard and considered in decision-making processes.</p>

<h2>2. Participation</h2>
<ul>
    <li><strong>Eligibility:</strong> Participation is open to all residents of the Local Government Unit (LGU) and stakeholders who are directly affected by the issues being consulted.</li>
    <li><strong>Methods of Participation:</strong> Residents can participate through various means, including:
        <ul>
            <li>Online surveys</li>
            <li>Public forums and meetings (both virtual and in-person)</li>
            <li>Submission of written comments via email or our webpage</li>
        </ul>
    </li>
    <li><strong>Registration:</strong> Some consultations may require prior registration. Participants will be informed accordingly.</li>
</ul>

<h2>3. Conduct of Participants</h2>
<p>Participants are expected to:</p>
<ul>
    <li>Engage respectfully and constructively.</li>
    <li>Refrain from using offensive language or making personal attacks.</li>
    <li>Stay on topic and provide relevant feedback.</li>
</ul>

<h2>4. Data Privacy</h2>
<p>Any personal information collected during consultations will be handled in accordance with applicable data protection laws. Participant information will be used solely for the purpose of consultation and will not be shared with third parties without consent.</p>

<h2>5. Feedback Collection</h2>
<p>Feedback will be collected through the methods specified in each consultation notice. Participants are encouraged to provide honest and constructive feedback, which will be reviewed by LGU officials and considered in the decision-making process.</p>

<h2>6. Duration of Consultation</h2>
<p>Each consultation will have a specified duration, which will be communicated in the consultation notice. Extensions may be granted at the discretion of the LGU, and participants will be informed of any changes.</p>

<h2>7. Intellectual Property</h2>
<p>All materials shared during consultations, including surveys, presentations, and other resources, are the property of the LGU. Participants are not permitted to reproduce or distribute these materials without permission.</p>

<h2>8. Limitation of Liability</h2>
<p>The LGU is not responsible for any issues arising from participation in the consultations, including but not limited to technical difficulties, errors in feedback collection, or misinterpretation of participant responses.</p>

<h2>9. Amendments to Terms</h2>
<p>These terms and conditions may be amended from time to time. Participants will be notified of any changes through the webpage or via email.</p>

<h2>10. Governing Law</h2>
<p>These terms and conditions are governed by the laws of the Philippines. Any disputes arising from these terms will be resolved in accordance with applicable laws.</p>

<h2>11. Contact Information</h2>
<p>For questions or concerns regarding these terms and conditions, please contact us at [Insert Contact Email/Phone Number].</p>
            </div>
            <div class="modal-footer">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="term1">
                    <label class="form-check-label" for="term1">I agree to Term 1</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="term2" disabled>
                    <label class="form-check-label" for="term2">I agree to Term 2</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="term3" disabled>
                    <label class="form-check-label" for="term3">I agree to Term 3</label>
                </div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Decline</button>
                <button type="button" class="btn btn-primary" id="acceptTerms">Accept</button>
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

        // Scroll event listener
        document.querySelector('.modal-body').onscroll = function() {
            const modalBody = this;
            const scrollTop = modalBody.scrollTop;
            const scrollHeight = modalBody.scrollHeight;
            const clientHeight = modalBody.clientHeight;

            // Check if scrolled to the bottom
            if (scrollTop + clientHeight >= scrollHeight) {
                // Enable checkboxes when scrolled to the bottom
                document.getElementById('term2').disabled = false;
                document.getElementById('term3').disabled = false;
            }
        };

        // Automatically check Term 2 and Term 3 when Term 1 is checked
        document.getElementById('term1').onclick = function() {
            const term2 = document.getElementById('term2');
            const term3 = document.getElementById('term3');
            
            if (!term2.disabled && !term3.disabled) {
                term2.checked = this.checked;
                term3.checked = this.checked;
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
