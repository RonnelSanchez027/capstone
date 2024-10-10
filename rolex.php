<?php
// Database connection
$conn = new mysqli("localhost:3307", "root", "", "admin_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null;

    if (isset($_POST['update']) && $id) {
        // Update logic
        $full_name = $_POST['full_name'];
        $job_title = $_POST['job_title'];
        $role = $_POST['role'];
        $status = $_POST['status'];

        // Update the record in the database
        $sql = "UPDATE roles SET full_name='$full_name', job_title='$job_title', role='$role', status='$status' WHERE id=$id";
        if (!$conn->query($sql)) {
            die("Update failed: " . $conn->error);
        }
    } elseif (isset($_POST['submit'])) {
        // Insert logic
        $full_name = $_POST['full_name'];
        $job_title = $_POST['job_title'];
        $role = $_POST['role'];
        $status = $_POST['status'];

        // Insert into database
        $sql = "INSERT INTO roles (full_name, job_title, role, status) VALUES ('$full_name', '$job_title', '$role', '$status')";
        if (!$conn->query($sql)) {
            die("Insert failed: " . $conn->error);
        }
    }

    // Redirect to the same page to avoid duplicate submission
    header("Location: rolex.php");
    exit; 
}
// Handle delete request
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM roles WHERE id=$id";
    if (!$conn->query($sql)) {
        die("Delete failed: " . $conn->error);
    }
    // Redirect to avoid refresh issues
    header("Location: rolex.php");
    exit;
}
// Fetch roles from the database
$sql = "SELECT * FROM roles";
$result = $conn->query($sql);
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
    <style>
        .dark-theme label{
            color: #e7e8f4;
        }
        .dark-theme .table td, .dark-mode .table th {
        color: #e7e8f4; /* Change text color to white */
        }
        .form-row {
            margin-bottom: 15px;
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
            <div class="submenu-item" onclick="location.href='#client-feedback'"><i class="fas fa-user-clock"></i>Client Feedback</div>
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
            <div class="submenu-item" onclick="location.href='#user-registration'"><i class="fas fa-user-clock"></i>User Registration</div>
            <div class="submenu-item" onclick="location.href='rolex.php'"><i class="fas fa-user-clock"></i>Role Assignment</div>
            <div class="submenu-item" onclick="location.href='#profile-management'"><i class="fas fa-user-clock"></i>Profile Management</div>
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
            <div class="submenu-item" onclick="location.href='#export-reports'"><i class="fas fa-user-clock"></i>Export & Share Reports</div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <h2>Role Management</h2>

    <form action="rolex.php" method="POST">
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="full_name">Full Name:</label>
                <input type="text" class="form-control" id="full_name" name="full_name" required>
            </div>
            <div class="form-group col-md-3">
                <label for="job_title">Job Title:</label>
                <input type="text" class="form-control" id="job_title" name="job_title" required>
            </div>
            <div class="form-group col-md-3">
                <label for="role">Role:</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="Admin">Admin</option>
                    <option value="Consultant">Consultant</option>
                    <option value="User">User</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>

        <input type="hidden" name="id" id="userId">
        <button type="submit" class="btn btn-primary" name="submit">Save Profile</button>
        <button type="submit" class="btn btn-secondary" name="update" style="display: none;">Update Profile</button>
    </form>

    <table class="table table-bordered mt-3">
        <thead class="thead-dark">
            <tr>
                <th>Full Name</th>
                <th>Job Title</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['full_name']}</td>
                            <td>{$row['job_title']}</td>
                            <td>{$row['role']}</td>
                            <td>{$row['status']}</td>
                            <td>
                                <button class='btn btn-warning btn-sm' onclick='editUser({$row['id']}, \"{$row['full_name']}\", \"{$row['job_title']}\", \"{$row['role']}\", \"{$row['status']}\")'><i class='fas fa-edit'></i></button>
                                <a href='rolex.php?delete={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete?\");'><i class='fas fa-trash'></i></a>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No profiles found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="script.js"></script> <!-- Link to JS -->
<script>
function editUser(id, fullName, jobTitle, role, status) {
    document.getElementById('userId').value = id;
    document.getElementById('full_name').value = fullName;
    document.getElementById('job_title').value = jobTitle;
    document.getElementById('role').value = role;
    document.getElementById('status').value = status;

    // Show update button and hide save button
    document.querySelector('button[name="submit"]').style.display = 'none';
    document.querySelector('button[name="update"]').style.display = 'inline-block';
}
</script>
</body>
</html>
