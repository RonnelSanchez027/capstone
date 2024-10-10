<?php
// Database connection
$connection = mysqli_connect("localhost:3307", "root", "", "admin_db");

// Handle comment update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_comment_id'])) {
    $comment_id = $_POST['update_comment_id'];
    $comment = $_POST['comment'];

    // Sanitize input
    $comment = mysqli_real_escape_string($connection, $comment);

    // Update comment in comments table
    $updateCommentQuery = "UPDATE comments SET comment = '$comment' WHERE id = $comment_id";
    mysqli_query($connection, $updateCommentQuery);

    // Redirect back to the consultation page
    header("Location: consultation.php");
    exit();
}

// Handle comment deletion
if (isset($_GET['delete_id'])) {
    $comment_id = $_GET['delete_id'];

    // Delete comment from comments table
    $deleteCommentQuery = "DELETE FROM comments WHERE id = $comment_id";
    mysqli_query($connection, $deleteCommentQuery);

    // Redirect back to the consultation page
    header("Location: consultation.php");
    exit();
}

// Fetch appointments
$appointmentsQuery = "SELECT * FROM appointment";
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
	<style>
		h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        input, select, textarea {
            background-color: #fff; /* Default input background */
            color: #0b0c18; /* Default input text color */
            border: 1px solid #ccc; /* Default input border */
        }
        input::placeholder, textarea::placeholder {
            color: #b0b0b0; /* Placeholder text color */
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
        .dark-mode .table {
            background-color: #2a2d3d; /* Dark mode table background */
            color: #e7e8f4; /* Dark mode table text color */
        }
        .table th, .table td {
            border: 1px solid #ccc; /* Default table border */
        }
        .dark-mode .table th, 
        .dark-mode .table td {
            border: 1px solid #444; /* Dark mode table border */
        }
		.dark-theme .forbid{
			color: #0b0c18;
		}
        .dark-theme .comment-text {
        color: #0b0c18; /* Set comment text color to black */
        }
        @media (max-width: 768px) {
        .table {
            font-size: 14px; /* Adjust font size for mobile */
        }
        .table th, .table td {
            padding: 10px; /* Adjust padding for smaller screens */
        }
    }
    @media (max-width: 480px) {
        .table {
            font-size: 12px; /* Further reduce font size for very small screens */
        }
        .table th, .table td {
            padding: 8px; /* Further reduce padding for better fit */
        }
        .container {
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

<title>Consultation</title>
</head>
<body>
    <div class="container">
        <h1>Consultation</h1>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Category</th>
                    <th>Subcategory</th>
                    <th>Details</th>
                    <th>Comments</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $appointmentCount = 1;
                while ($row = mysqli_fetch_assoc($appointmentsResult)) : ?>
                    <tr>
                        <td><?php echo $appointmentCount++; ?></td>
                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                        <td><?php echo htmlspecialchars($row['subcategory']); ?></td>
                        <td><?php echo htmlspecialchars($row['details']); ?></td>
                        <td>
                            <?php
                            $commentsQuery = "SELECT * FROM comments WHERE appointment_id = " . $row['id'];
                            $commentsResult = mysqli_query($connection, $commentsQuery);
                            $comments = mysqli_fetch_all($commentsResult, MYSQLI_ASSOC);

                            if (empty($comments)): ?>
                                <p class="comment-text">No comments yet.</p>
                            <?php else: 
                                foreach ($comments as $commentRow): ?>
                                    <p class="comment-text"><?php echo htmlspecialchars($commentRow['comment']); ?> 
                                        <small>(<?php echo date('h:i A, M d Y', strtotime($commentRow['created_at'])); ?>)</small>
                                    </p>
                                <?php endforeach; 
                            endif; ?>
                        </td>
                        <td>
                            <?php if (empty($comments)): ?>
                                <!-- Add Comment Button -->
                                <button class="btn btn-primary" data-toggle="modal" data-target="#addCommentModal<?php echo $row['id']; ?>">Add</button>

                                <!-- Add Comment Modal -->
                                <div class="modal fade" id="addCommentModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="addCommentModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addCommentModalLabel">Add Comment</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form method="POST" action="add_comment.php">
                                                <div class="modal-body">
                                                    <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                                                    <div class="form-group">
                                                        <textarea class="form-control" name="comment" placeholder="Add a comment..." required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Add Comment</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- Edit and Delete buttons for existing comments -->
                                <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#editCommentModal<?php echo $comments[0]['id']; ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="?delete_id=<?php echo $comments[0]['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </a>

                                <!-- Edit Comment Modal -->
                                <div class="modal fade" id="editCommentModal<?php echo $comments[0]['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editCommentModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editCommentModalLabel">Edit Comment</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="update_comment_id" value="<?php echo $comments[0]['id']; ?>">
                                                    <div class="form-group">
                                                        <textarea class="form-control" name="comment" required><?php echo htmlspecialchars($comments[0]['comment']); ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Update Comment</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="script.js"></script> <!-- Link to JS -->

    </body>
</html>