<?php
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "admin_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sync users from users table to modified_users table
$sync_sql = "
    INSERT INTO modified_users (username, email)
    SELECT username, email FROM users
    WHERE NOT EXISTS (
        SELECT 1 FROM modified_users WHERE modified_users.username = users.username
    )
";
$conn->query($sync_sql);

// Cleanup: Remove users from modified_users who no longer exist in users
$cleanup_sql = "
    DELETE FROM modified_users
    WHERE username NOT IN (
        SELECT username FROM users
    )
";
$conn->query($cleanup_sql);

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $id = $_POST['id'];

        if ($_POST['action'] == 'update') {
            $full_name = $_POST['full_name'] ?? '';
            $job = $_POST['job'] ?? '';
            $role = $_POST['role'] ?? '';
            $status = $_POST['status'] ?? '';

            $sql = "UPDATE modified_users SET full_name='$full_name', job='$job', role='$role', status='$status' WHERE id=$id";
            if ($conn->query($sql) !== TRUE) {
                echo "Error updating user: " . $conn->error;
            }
        } elseif ($_POST['action'] == 'delete') {
            // Clear all fields for the user
            $sql = "UPDATE modified_users SET full_name='', job='', role='', status='' WHERE id=$id";
            if ($conn->query($sql) !== TRUE) {
                echo "Error deleting user fields: " . $conn->error;
            }
        }
    }
}

$sql = "SELECT * FROM modified_users";
$result = $conn->query($sql);

if ($result === FALSE) {
    die("Error: " . $conn->error);
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
        <h1 style="text-align: center;">User Roles Management</h1>
        <table class="table table-bordered mt-3">
            <thead class="thead-dark">
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Full Name</th>
                    <th>Job</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $full_name = $row['full_name'] ?: 'No full name yet';
                        $job = $row['job'] ?: 'No job yet';
                        $role = $row['role'] ?: 'No role assigned';
                        $status = $row['status'] ?: 'No status assigned';

                        echo "<tr>
                                <td>{$row['username']}</td>
                                <td>{$row['email']}</td>
                                <td>$full_name</td>
                                <td>$job</td>
                                <td>$role</td>
                                <td>$status</td>
                                <td>";

                        // Show add icon if any of the fields are empty
                        if (empty($row['full_name']) || empty($row['job']) || empty($row['role']) || empty($row['status'])) {
                            echo "<button class='btn btn-success' data-toggle='modal' data-target='#addModal{$row['id']}' title='Add Info'>
                                    <i class='fas fa-plus'></i>
                                  </button>";
                        } else {
                            echo "<button class='btn btn-primary' data-toggle='modal' data-target='#editModal{$row['id']}' title='Edit Info'>
                                    <i class='fas fa-edit'></i>
                                  </button>
                                  <form method='POST' action='roles.php' style='display:inline;'>
                                      <input type='hidden' name='id' value='{$row['id']}' />
                                      <input type='hidden' name='action' value='delete' />
                                      <button type='submit' class='btn btn-danger' title='Delete Info' onclick='return confirm(\"Are you sure you want to delete all info for {$row['username']}?\");'>
                                          <i class='fas fa-trash'></i>
                                      </button>
                                  </form>";
                        }

                        echo "</td></tr>";

                        // Edit Modal
                        echo "
                        <div class='modal fade' id='editModal{$row['id']}' tabindex='-1' role='dialog' aria-labelledby='editModalLabel{$row['id']}' aria-hidden='true'>
                            <div class='modal-dialog' role='document'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title' id='editModalLabel{$row['id']}'>Edit User</h5>
                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                            <span aria-hidden='true'>&times;</span>
                                        </button>
                                    </div>
                                    <div class='modal-body'>
                                        <form method='POST' action='roles.php'>
                                            <input type='hidden' name='id' value='{$row['id']}' />
                                            <input type='hidden' name='action' value='update' />
                                            <div class='form-group'>
                                                <label for='full_name'>Full Name</label>
                                                <input type='text' class='form-control' name='full_name' value='{$row['full_name']}' />
                                            </div>
                                            <div class='form-group'>
                                                <label for='job'>Job</label>
                                                <input type='text' class='form-control' name='job' value='{$row['job']}' />
                                            </div>
                                            <div class='form-group'>
                                                <label for='role'>Role</label>
                                                <select class='form-control' name='role'>
                                                    <option value='Admin' " . ($row['role'] == 'Admin' ? 'selected' : '') . ">Admin</option>
                                                    <option value='User' " . ($row['role'] == 'User' ? 'selected' : '') . ">User</option>
                                                    <option value='Moderator' " . ($row['role'] == 'Moderator' ? 'selected' : '') . ">Moderator</option>
                                                    <option value='Editor' " . ($row['role'] == 'Editor' ? 'selected' : '') . ">Editor</option>
                                                    <option value='Viewer' " . ($row['role'] == 'Viewer' ? 'selected' : '') . ">Viewer</option>
                                                </select>
                                            </div>
                                            <div class='form-group'>
                                                <label for='status'>Status</label>
                                                <select class='form-control' name='status'>
                                                    <option value='Active' " . ($row['status'] == 'Active' ? 'selected' : '') . ">Active</option>
                                                    <option value='Inactive' " . ($row['status'] == 'Inactive' ? 'selected' : '') . ">Inactive</option>
                                                    <option value='Terminated' " . ($row['status'] == 'Terminated' ? 'selected' : '') . ">Terminated</option>
                                                </select>
                                            </div>
                                            <button type='submit' class='btn btn-primary'>Save changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>";

                        // Add Modal for Adding Info
                        echo "
                        <div class='modal fade' id='addModal{$row['id']}' tabindex='-1' role='dialog' aria-labelledby='addModalLabel{$row['id']}' aria-hidden='true'>
                            <div class='modal-dialog' role='document'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title' id='addModalLabel{$row['id']}'>Add Info for {$row['username']}</h5>
                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                            <span aria-hidden='true'>&times;</span>
                                        </button>
                                    </div>
                                    <div class='modal-body'>
                                        <form method='POST' action='roles.php'>
                                            <input type='hidden' name='id' value='{$row['id']}' />
                                            <input type='hidden' name='action' value='update' />
                                            <div class='form-group'>
                                                <label for='full_name'>Full Name</label>
                                                <input type='text' class='form-control' name='full_name' value='' />
                                            </div>
                                            <div class='form-group'>
                                                <label for='job'>Job</label>
                                                <input type='text' class='form-control' name='job' value='' />
                                            </div>
                                            <div class='form-group'>
                                                <label for='role'>Role</label>
                                                <select class='form-control' name='role'>
                                                    <option value=''>Select Role</option>
                                                    <option value='Admin'>Admin</option>
                                                    <option value='User'>User</option>
                                                    <option value='Moderator'>Moderator</option>
                                                    <option value='Editor'>Editor</option>
                                                    <option value='Viewer'>Viewer</option>
                                                </select>
                                            </div>
                                            <div class='form-group'>
                                                <label for='status'>Status</label>
                                                <select class='form-control' name='status'>
                                                    <option value=''>Select Status</option>
                                                    <option value='Active'>Active</option>
                                                    <option value='Inactive'>Inactive</option>
                                                    <option value='Terminated'>Terminated</option>
                                                </select>
                                            </div>
                                            <button type='submit' class='btn btn-success'>Add Info</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No users found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="script.js"></script> <!-- Link to JS -->
</body>
</html>

<?php
$conn->close();
?>
