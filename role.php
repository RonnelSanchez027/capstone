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
    header("Location: role.php");
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
    header("Location: role.php");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Role Management</title>
    <style>
        .form-row {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Role Management</h2>

    <form action="role.php" method="POST">
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
                                <a href='role.php?delete={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete?\");'><i class='fas fa-trash'></i></a>
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
