<?php
session_start();
include '../includes/auth.php'; // Ensure the user is an authenticated event creator
include '../includes/db_connect.php'; // Include database connection

// Get the event creator's ID from the session
$creator_id = $_SESSION['user_id'];

// Delete event
if (isset($_GET['delete'])) {
    $event_id = intval($_GET['delete']);
    $query = "DELETE FROM events WHERE id = $event_id AND created_by = $creator_id AND is_approved = 0"; 
    mysqli_query($conn, $query);
    $_SESSION['success'] = "Event deleted successfully.";
    header("Location: manage_events_creator.php");
    exit();
}

// Fetch all events created by this user
$query = "SELECT * FROM events WHERE created_by = $creator_id ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage My Events</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
        }
        .table td, .table th {
            vertical-align: middle;
        }
        .action-buttons a {
            margin-right: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">Manage My Events</h2>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Description</th>
                <th>Event Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($event = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $event['event_name']; ?></td>
                    <td><?php echo $event['description']; ?></td>
                    <td><?php echo date("F j, Y", strtotime($event['event_date'])); ?></td>
                    <td>
                        <?php echo $event['is_approved'] ? 'Approved' : 'Pending'; ?>
                    </td>
                    <td class="action-buttons">
                        <?php if (!$event['is_approved']): ?>
                            <a href="edit_event.php?id=<?php echo $event['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="manage_events_creator.php?delete=<?php echo $event['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                        <?php else: ?>
                            <button class="btn btn-secondary btn-sm" disabled>Approved</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JavaScript -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
