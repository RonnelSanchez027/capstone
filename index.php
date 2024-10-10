<?php
session_start();

// Check if the user is already logged in
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header("Location: login.html");
    exit();
}

// At this point, the user is logged in
// You can retrieve user information based on the user_id stored in the session
require 'db.php'; // Make sure to include your database connection file

// Fetch user data using the user_id from session
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Now you can use $row to access user data, like $row['username'], $row['email'], etc.
} else {
    // If no user found, you may want to log out the user
    session_destroy();
    header("Location: login.html");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Link to CSS -->
    <title>Public Consultation Dashboard</title>
</head>
<body>

<div class="taskbar">
    <i class="fas fa-bars taskbar-icon" id="toggleSidebar"></i>
    <div class="ml-auto">
        <i class="fas fa-sun taskbar-icon" id="themeToggle"></i>
        <i class="fas fa-comments taskbar-icon"></i>
        <i class="fas fa-bell taskbar-icon"></i>
        <i class="fas fa-user taskbar-icon" id="userIcon"></i>
    </div>
</div>

<div id="floatingTab" class="floating-tab" style="display: none;">
    <a href="login.html" onclick="logout()">Log Out</a>
</div>


<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="index.php" class="sidebar-header-link">
            <i class="fas fa-landmark"></i>
            <h4>Public Consultation</h4>
        </a>
    </div>
    <div class="menu-item">
        <a href="#consultation-scheduling" class="menu-link">
            <div class="d-flex justify-content-between" onclick="toggleSubmenu('schedulingMenu')">
                <span><i class="fas fa-calendar-alt"></i> Consultation Scheduling</span>
                <i class="fas fa-chevron-down"></i>
            </div>
        </a>
        <div class="submenu" id="schedulingMenu">
            <div class="submenu-item" onclick="location.href='appointment.php'"><i class="fas fa-user-clock"></i>Appointment Booking</div>
            <div class="submenu-item" onclick="location.href='consultation.php'"><i class="fas fa-user-clock"></i>Consultation Management</div>
            <div class="submenu-item" onclick="location.href='schedule.php'"><i class="fas fa-user-clock"></i>Scheduling Management</div>
        </div>
    </div>
    <div class="menu-item">
        <a href="#consultation-tracking" class="menu-link">
            <div class="d-flex justify-content-between" onclick="toggleSubmenu('trackingMenu')">
                <span><i class="fas fa-chart-line"></i> Consultation Tracking</span>
                <i class="fas fa-chevron-down"></i>
            </div>
        </a>
        <div class="submenu" id="trackingMenu">
            <div class="submenu-item" onclick="location.href='records.php'"><i class="fas fa-user-clock"></i>Consultation Records</div>
            <div class="submenu-item" onclick="location.href='tracking.php'"><i class="fas fa-user-clock"></i>Status Tracking</div>
        </div>
    </div>
    <div class="menu-item">
        <a href="#user-management" class="menu-link">
            <div class="d-flex justify-content-between" onclick="toggleSubmenu('userMenu')">
                <span><i class="fas fa-user-friends"></i> User Management</span>
                <i class="fas fa-chevron-down"></i>
            </div>
        </a>
        <div class="submenu" id="userMenu">
            <div class="submenu-item" onclick="location.href='roles.php'"><i class="fas fa-user-clock"></i>Role Assignment</div>
        </div>
    </div>
    <div class="menu-item">
        <a href="#reporting-analytics" class="menu-link">
            <div class="d-flex justify-content-between" onclick="toggleSubmenu('reportMenu')">
                <span><i class="fas fa-chart-line"></i> Reporting & Analytics</span>
                <i class="fas fa-chevron-down"></i>
            </div>
        </a>
        <div class="submenu" id="reportMenu">
            <div class="submenu-item" onclick="location.href='report.html'"><i class="fas fa-user-clock"></i>Consultation Reports</div>
            <div class="submenu-item" onclick="location.href='analytics.html'"><i class="fas fa-user-clock"></i>Analytics Dashboard</div>
        </div>
    </div>
</div>

<div class="container-fluid" id="content">
    <h1 class="header">Public Consultation Dashboard</h1>
    
    <div class="row">
        <div class="col-md-4">
            <div class="section">
                <h2 class="special-paragraph"><i class="fas fa-calendar-alt icon"></i> Module 1: Consultation Scheduling</h2>
                <div class="submodule-title">Appointment Booking</div>
                <div>Online booking form with calendar integration and notifications.</div><br>
                <div class="submodule-title">Scheduling Management</div>
                <div>Manage scheduled consultations, rescheduling, and availability.</div><br>
                <div class="submodule-title">Consultant Availability</div>
                <div>Update consultant shifts and integrate personal calendars.</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="section">
                <h2><i class="fas fa-chart-line icon"></i> Module 2: Consultation Tracking</h2>
                <div class="submodule-title">Consultation Records</div>
                <div>Create records with notes, actions, and document management.</div><br>
                <div class="submodule-title">Status Tracking</div>
                <div>Track progress with status updates and follow-ups.</div><br>
                <div class="submodule-title">Client Feedback</div>
                <div>Collect and analyze client feedback with reporting features.</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="section">
                <h2><i class="fas fa-user-friends icon"></i> Module 3: User Management</h2>
                <div class="submodule-title">User Registration</div>
                <div>Sign-up, authentication, and password recovery.</div><br>
                <div class="submodule-title">Role Assignment</div>
                <div>Define roles and manage access control.</div><br>
                <div class="submodule-title">Profile Management</div>
                <div>Update personal information and preferences.</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="section">
                <h2><i class="fas fa-file-alt icon"></i> Module 4: Reporting & Analytics</h2>
                <div class="submodule-title">Consultation Reports</div>
                <div>Generate customizable reports based on various parameters.</div><br>
                <div class="submodule-title">Analytics Dashboard</div>
                <div>Visualize data with charts and key performance indicators.</div><br>
                <div class="submodule-title">Export & Share Reports</div>
                <div>Export reports in multiple formats and share easily.</div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="section">
                <h2>Dashboard Overview</h2>
                <div class="metric">
                    <h5>Total Consultations:</h5>
                    <span class="text-primary" style="font-size: 30px;">150</span>
                </div>
                <div class="metric">
                    <h5>Feedback Received:</h5>
                    <span class="text-primary" style="font-size: 30px;">500</span>
                </div>
                <div class="metric">
                    <h5>Upcoming Events:</h5>
                    <span class="text-primary" style="font-size: 30px;">3</span>
                </div>
                <div>Your active participation is vital for community improvement!</div>
                <div>Don't miss the next consultation meeting on Nov 15, 2024!</div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="script.js"></script> <!-- Link to JS -->
</body>
</html>
