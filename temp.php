<?php
// Database connection
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "admin_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch appointments and their comments
$sql = "
    SELECT a.id, a.category, a.subcategory, a.details, c.comment, c.created_at 
    FROM appointment a
    LEFT JOIN comments c ON a.id = c.appointment_id
";
$result = $conn->query($sql);
$appointments = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
} else {
    // Handle case where no appointments are found
    echo "No appointments found.";
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments and Comments</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        #search {
            max-width: 30%; /* Set the width to 30% for desktop */
        }
        .table-responsive {
            max-width: 100%; /* Adjust to your preference */
            overflow-x: auto; /* Allows horizontal scrolling */
        }
        @media (max-width: 768px) {
            #search {
                max-width: 80%; /* Set the width to 80% for mobile */
            }
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2>Appointments and Comments</h2>
    <div class="input-group mb-3" id="searchContainer">
        <input type="text" id="search" class="form-control" placeholder="Search for appointments...">
        <div class="input-group-append">
            <button id="searchButton" class="btn btn-outline-secondary" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered mt-3" id="appointmentsTable">
            <thead class="thead-dark">
                <tr>
                    <th>Category</th>
                    <th>Subcategory</th>
                    <th>Details</th>
                    <th>Comment</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appointment['category']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['subcategory']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['details']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['comment'] ?: 'No comments'); ?></td>
                        <td><?php echo htmlspecialchars($appointment['created_at'] ?: 'N/A'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    // Search functionality
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
