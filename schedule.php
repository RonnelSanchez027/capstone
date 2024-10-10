<?php
$servername = "localhost:3307"; // Change if necessary
$username = "root";        // Your database username
$password = "";            // Your database password
$dbname = "admin_db"; // Change to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $date = $_POST['date'];
        $time = $_POST['time'];
        $venue = $_POST['venue'];
        $topic = $_POST['topic'];
        $conn->query("INSERT INTO schedule (date, time, venue, topic) VALUES ('$date', '$time', '$venue', '$topic')");
    } elseif (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $venue = $_POST['venue'];
        $topic = $_POST['topic'];
        $conn->query("UPDATE schedule SET date='$date', time='$time', venue='$venue', topic='$topic' WHERE id=$id");
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $conn->query("DELETE FROM schedule WHERE id=$id");
    }
}

// Fetch data
$result = $conn->query("SELECT * FROM schedule");
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
	<style>
		h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #dee2e6; /* Border for table */
        }
        .table th, .table td {
            padding: 15px;
            text-align: center;
            border: 1px solid #dee2e6; /* Border for cells */
        }
        .table tbody tr {
            background-color: #f2f2f2; /* Grayish white background for table rows in light mode */
        }
        .table tbody tr td {
            background-color: #ffffff; /* Pure white background for content cells in light mode */
        }
        .dark-mode .table tbody tr {
            background-color: #ffffff; /* Keep row background white in dark mode */
        }
        .dark-mode .table tbody tr td {
            background-color: #1c1f23; /* Dark background for content cells in dark mode */
            color: #e7e8f4; /* Light text for content cells in dark mode */
        }
        .thead-light {
            background-color: #cfd8fc; /* Light mode header color */
        }
        .dark-mode .thead-light {
            background-color: #3a3d45; /* Dark mode header color */
        }
        body.dark-theme label{
            color: #e7e8f4;
        }
		@media (max-width: 480px) {
    .table {
        font-size: 12px; /* Reduce font size for small screens */
    }
    .table th, .table td {
        padding: 8px; /* Reduce padding for better fit */
    }
    .table-container {
        overflow-x: auto; /* Ensure horizontal scrolling */
    }
}
	</style>
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
            <div class="submenu-item" onclick="location.href='report.html'"><i class="fas fa-user-clock"></i>Consultation Reports</div>
            <div class="submenu-item" onclick="location.href='analytics.html'"><i class="fas fa-user-clock"></i>Analytics Dashboard</div>
        </div>
    </div>
</div>

<div class="container mt-5">
        <h1 class="mb-4">Schedule Management</h1>
        
        <form method="post" class="mb-4">
            <input type="hidden" name="id" id="edit-id">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label class="labels" for="edit-date">Date</label>
                    <input type="date" name="date" id="edit-date" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="edit-time">Time</label>
                    <input type="time" name="time" id="edit-time" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="edit-venue">Venue</label>
                    <input type="text" name="venue" id="edit-venue" class="form-control" placeholder="Venue" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="edit-topic">Topic</label>
                    <input type="text" name="topic" id="edit-topic" class="form-control" placeholder="Topic" required>
                </div>
            </div>
            <button type="submit" name="add" class="btn btn-primary">Add</button>
            <button type="submit" name="edit" style="display: none;" id="edit-btn" class="btn btn-warning">Update</button>
        </form>

        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Venue</th>
                    <th>Topic</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['date'] ?></td>
                    <td><?= date('h:i A', strtotime($row['time'])) ?></td>
                    <td><?= $row['venue'] ?></td>
                    <td><?= $row['topic'] ?></td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="editEntry(<?= $row['id'] ?>, '<?= $row['date'] ?>', '<?= $row['time'] ?>', '<?= $row['venue'] ?>', '<?= $row['topic'] ?>')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="script.js"></script> <!-- Link to JS -->
<script>
	function editEntry(id, date, time, venue, topic) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-date').value = date;
            document.getElementById('edit-time').value = time;
            document.getElementById('edit-venue').value = venue;
            document.getElementById('edit-topic').value = topic;
            document.getElementById('edit-btn').style.display = 'inline';
        }
</script>
</body>
</html>