<?php
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "admin_db";

// Create connection
$connection = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Fetch appointments with dynamic status and comments
$appointmentsQuery = "
    SELECT a.id, a.category, a.subcategory, a.details, 
           CASE WHEN COUNT(c.comment) > 0 THEN 'Completed' ELSE 'In Progress' END AS status,
           GROUP_CONCAT(c.comment SEPARATOR '<br>') AS comments 
    FROM appointment a
    LEFT JOIN comments c ON a.id = c.appointment_id
    GROUP BY a.id
";
$appointmentsResult = mysqli_query($connection, $appointmentsQuery);
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
        #search {
            max-width: 30%;
        }
        @media (max-width: 768px) {
            #search {
                max-width: 80%;
            }
        }
        .clickable-row {
            cursor: pointer;
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
        <a href="index.html" class="sidebar-header-link">
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
            <div class="submenu-item" onclick="location.href='#status-tracking'"><i class="fas fa-user-clock"></i>Status Tracking</div>
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
            <div class="submenu-item" onclick="location.href='#role-assignment'"><i class="fas fa-user-clock"></i>Role Assignment</div>
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
    <h2>Appointments and Comments</h2>
    <div class="input-group mb-3">
        <input type="text" id="search" class="form-control" placeholder="Search for appointments...">
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>

    <table class="table table-bordered mt-3" id="appointmentsTable">
        <thead class="thead-dark">
            <tr>
                <th>Category</th>
                <th>Subcategory</th>
                <th>Details</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($appointment = mysqli_fetch_assoc($appointmentsResult)): ?>
                <tr data-id="<?php echo $appointment['id']; ?>" class="clickable-row">
                    <td><?php echo htmlspecialchars($appointment['category']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['subcategory']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['details']); ?></td>
                    <td style="background-color: <?php echo htmlspecialchars($appointment['status'] === 'Completed' ? '#90EE90' : 'yellow'); ?>;">
                        <?php echo htmlspecialchars($appointment['status']); ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal for Comments -->
<div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Comments</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="commentContent"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="script.js"></script>
<script>
$(document).ready(function() {
    $('.clickable-row').click(function() {
        const appointmentId = $(this).data('id');
        // Fetch the specific comment for the selected appointment
        $.ajax({
            url: 'fetch_comment.php', // Ensure this file fetches the comment
            type: 'POST',
            data: { id: appointmentId },
            success: function(data) {
                $('#commentContent').html(data); // Display only the comment in the modal
                $('#commentModal').modal('show'); // Show the modal
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: ", status, error);
            }
        });
    });

    $('#search').on('input', function() {
        const query = $(this).val().toLowerCase();
        $('#appointmentsTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(query) > -1);
        });
    });
});
</script>

</body>
</html>

<?php
// Close connection
$connection->close();
?>
