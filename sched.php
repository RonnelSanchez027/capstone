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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Schedule Management</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Schedule Management</h1>
        
        <form method="post" class="mb-4">
            <input type="hidden" name="id" id="edit-id">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="edit-date">Date</label>
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
                    <td><?= $row['time'] ?></td>
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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
