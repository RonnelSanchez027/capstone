<?php
include 'fetch_data.php';
$data = fetchAnalyticsData(); // Fetch data using the updated function
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Analytics Dashboard</title>
    <style>
        .chart-container {
            position: relative;
            margin: auto;
            height: 40vh;
            width: 80%;
            margin-bottom: 40px;
        }
    </style>
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
    <a href="#" onclick="logout()">Log Out</a>
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
            <div class="submenu-item" onclick="location.href='reports.php'"><i class="fas fa-user-clock"></i>Analytics Dashboard</div>
        </div>
    </div>
</div>

<div class="container">
    <h1 class="text-center">Public Consultation Analytics Dashboard</h1>

    <div class="row">
        <div class="col-md-6">
            <h3>Users Overview</h3>
            <div class="chart-container">
                <canvas id="userChart"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <h3>Total Appointments</h3>
            <div class="chart-container">
                <canvas id="appointmentChart"></canvas>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <h3>Total Comments (Answered Appointments)</h3>
            <div class="chart-container">
                <canvas id="commentsChart"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <h3>Scheduled Events (Monthly)</h3>
            <div class="chart-container">
                <canvas id="scheduleChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="script.js"></script> <!-- Link to JS -->
<script>
    const usernames = <?php echo json_encode($data['usernames']); ?>;
    const appointmentCount = <?php echo $data['appointmentCount']; ?>;
    const commentsCount = <?php echo $data['commentsCount']; ?>;
    const scheduleMonths = <?php echo json_encode($data['scheduleMonths']); ?>;
    const scheduleCounts = <?php echo json_encode($data['scheduleCounts']); ?>;

    // User Chart
    const ctxUser = document.getElementById('userChart').getContext('2d');
    const userChart = new Chart(ctxUser, {
        type: 'bar', // Bar chart for individual users
        data: {
            labels: usernames,
            datasets: [{
                label: 'Users',
                data: Array(usernames.length).fill(1), // Each user has a count of 1
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'User Count'
                    }
                }
            }
        }
    });

    // Appointment Chart
    const ctxAppointment = document.getElementById('appointmentChart').getContext('2d');
    const appointmentChart = new Chart(ctxAppointment, {
        type: 'bar', // Bar chart for appointments
        data: {
            labels: ['Total Appointments'],
            datasets: [{
                label: 'Appointments',
                data: [appointmentCount],
                backgroundColor: 'rgba(153, 102, 255, 0.6)',
            }]
        }
    });

    // Comments Chart
    const ctxComments = document.getElementById('commentsChart').getContext('2d');
    const commentsChart = new Chart(ctxComments, {
        type: 'pie', // Pie chart for comments
        data: {
            labels: ['Answered Comments'],
            datasets: [{
                data: [commentsCount],
                backgroundColor: ['rgba(255, 159, 64, 0.6)'],
            }]
        }
    });

    // Schedule Chart (Changed to Doughnut Chart)
const ctxSchedule = document.getElementById('scheduleChart').getContext('2d');
const scheduleChart = new Chart(ctxSchedule, {
    type: 'doughnut', // Change to doughnut chart
    data: {
        labels: scheduleMonths,
        datasets: [{
            label: 'Scheduled Events',
            data: scheduleCounts,
            backgroundColor: [
                'rgba(54, 162, 235, 0.6)',
                'rgba(255, 99, 132, 0.6)',
                'rgba(255, 206, 86, 0.6)',
                'rgba(75, 192, 192, 0.6)',
                'rgba(153, 102, 255, 0.6)',
                'rgba(255, 159, 64, 0.6)',
            ],
        }]
    }
});
</script>
</body>
</html>
