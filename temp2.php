<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Feedback Management</h1>
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addFeedbackModal">Add Feedback</button>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Appointment ID</th>
                <th>Comment ID</th>
                <th>Feedback</th>
                <th>Rating</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $connection = mysqli_connect("localhost:3307", "root", "", "admin_db");
            $query = "SELECT * FROM feedbacks";
            $result = mysqli_query($connection, $query);

            while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo $row['appointment_id']; ?></td>
                    <td><?php echo $row['comment_id']; ?></td>
                    <td><?php echo $row['feedback']; ?></td>
                    <td><?php echo $row['rating']; ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="openEditModal('<?php echo $row['id']; ?>', '<?php echo addslashes($row['feedback']); ?>', '<?php echo $row['rating']; ?>')">Edit</button>
                        <form action="delete_feedback.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal for Adding Feedback -->
<div class="modal fade" id="addFeedbackModal" tabindex="-1" aria-labelledby="addFeedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="add_feedback.php">
                <div class="modal-header">
                    <h5 class="modal-title">Add Feedback</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="appointment_id">Appointment ID:</label>
                        <input type="number" class="form-control" name="appointment_id" required>
                    </div>
                    <div class="form-group">
                        <label for="comment_id">Comment ID:</label>
                        <input type="number" class="form-control" name="comment_id" required>
                    </div>
                    <div class="form-group">
                        <label for="feedback">Feedback:</label>
                        <textarea class="form-control" name="feedback" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="rating">Rating:</label>
                        <input type="number" class="form-control" name="rating" min="1" max="5" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit Feedback</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Editing Feedback -->
<div class="modal fade" id="editFeedbackModal" tabindex="-1" aria-labelledby="editFeedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="edit_feedback.php">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Feedback</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="form-group">
                        <label for="edit_feedback">Feedback:</label>
                        <textarea class="form-control" name="feedback" id="edit_feedback" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_rating">Rating:</label>
                        <input type="number" class="form-control" name="rating" id="edit_rating" min="1" max="5" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Feedback</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditModal(id, feedback, rating) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_feedback').value = feedback;
        document.getElementById('edit_rating').value = rating;
        $('#editFeedbackModal').modal('show');
    }
</script>
</body>
</html>
