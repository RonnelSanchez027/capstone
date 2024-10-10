<?php
session_start();
include 'db.php'; // Include database connection

// Check if the signup was successful
$signup_success = isset($_SESSION['signup_success']) ? $_SESSION['signup_success'] : false;
if ($signup_success) {
    unset($_SESSION['signup_success']);
}

// Initialize error message
$error_message = "";

// Process login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $error_message = "Please enter both username and password.";
    } else {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: index.php");
                exit();
            } else {
                $error_message = "Incorrect password.";
            }
        } else {
            $error_message = "User not found.";
        }

        $stmt->close();
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
    <title>Login Form</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
            transition: background-image 0.5s ease;
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
            background-image: url('pc.jpg');
            background-size: cover;
            background-position: center;
        }
        .form-section {
            flex: 40%;
            padding: 40px;
            background: white;
            position: relative;
        }
        .form-check {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .form-control {
            padding-right: 40px;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
        }
        @media (max-width: 768px) {
            body {
                background-image: url('pc.jpg');
                background-size: cover;
                background-position: center;
            }
            .container {
                flex-direction: column;
                background: transparent;
            }
            .image-section {
                display: none;
            }
            .form-section {
                padding: 20px;
                background-color: rgba(255, 255, 255, 0.9);
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="image-section"></div>
    <div class="form-section">
        <h2>Hello,</h2>
        <h3>Welcome Back</h3>

        <!-- Display error message above username input -->
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form action="" method="post"> <!-- Form action to the same page -->
            <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="Username or Email" required>
            </div>
            <div class="form-group position-relative">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <span class="toggle-password" toggle="#password">
                    <i class="fas fa-eye-slash" id="toggle-icon"></i>
                </span>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe">
                <label class="form-check-label" for="rememberMe" style="font-size: 0.9rem;">Remember Password</label>
                <a href="#" class="text-secondary" style="font-size: 0.9rem;">Forgot Password?</a>
            </div>
            <button type="submit" class="btn btn-primary btn-block mt-3">Login</button>
        </form>
        <p class="mt-3">Don't have an account? <a href="signup.php">Click here</a></p>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $(".toggle-password").click(function() {
            const passwordField = $(this).prev();
            const icon = $(this).find("i");
            if (passwordField.attr("type") === "password") {
                passwordField.attr("type", "text");
                icon.removeClass("fa-eye-slash").addClass("fa-eye");
            } else {
                passwordField.attr("type", "password");
                icon.removeClass("fa-eye").addClass("fa-eye-slash");
            }
        });
    });
</script>
</body>
</html>
