<?php
include '../includes/auth.php'; // Ensure the admin is logged in
require '../includes/db_connect.php'; // Database connection

// Handle approve event request
if (isset($_GET['approve'])) {
    $event_id = intval($_GET['approve']);
    $query = "UPDATE events SET is_approved = 1 WHERE id = $event_id";
    mysqli_query($conn, $query);
    header('Location: manage_events.php');
    exit();
}

// Handle delete event request
if (isset($_GET['delete'])) {
    $event_id = intval($_GET['delete']);
    $query = "DELETE FROM events WHERE id = $event_id";
    mysqli_query($conn, $query);
    header('Location: manage_events.php');
    exit();
}

// Handle setting auto-approve for Event Creators
if (isset($_POST['update_auto_approve'])) {
    $created_by = intval($_POST['created_by']);
    $auto_approve = intval($_POST['auto_approve']);
    $query = "UPDATE users SET auto_approve = $auto_approve WHERE id = $created_by";
    mysqli_query($conn, $query);
    header('Location: manage_events.php');
    exit();
}

// Fetch all events
$query = "
    SELECT events.*, users.name as creator_name, users.auto_approve 
    FROM events
    JOIN users ON events.created_by = users.id";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events - Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .btn-approve {
            color: white;
            background-color: #28a745;
        }
        .btn-delete {
            color: white;
            background-color: #dc3545;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="mb-4">Manage Events</h1>

    <!-- Display Events in a Table -->
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Event Name</th>
                <th>Description</th>
                <th>Creator</th>
                <th>Auto-Approve</th>
                <th>Approval Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['event_name']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td><?php echo $row['creator_name']; ?></td>

                <!-- Auto-Approve Checkbox -->
                <td>
                    <form method="POST" action="manage_events.php">
                        <input type="hidden" name="created_by" value="<?php echo $row['created_by']; ?>">
                        <input type="hidden" name="auto_approve" value="<?php echo $row['auto_approve'] ? '0' : '1'; ?>">
                        <button type="submit" name="update_auto_approve" class="btn btn-<?php echo $row['auto_approve'] ? 'danger' : 'success'; ?> btn-sm">
                            <?php echo $row['auto_approve'] ? 'Disable Auto-Approve' : 'Enable Auto-Approve'; ?>
                        </button>
                    </form>
                </td>

                <!-- Approval Status -->
                <td>
                    <?php if ($row['is_approved']): ?>
                        <span class="badge badge-success">Approved</span>
                    <?php else: ?>
                        <span class="badge badge-warning">Pending</span>
                    <?php endif; ?>
                </td>
                <td><?php echo $row['created_at']; ?></td>

                <td>
                    <!-- Approve Event Button -->
                    <?php if (!$row['is_approved']): ?>
                    <a href="manage_events.php?approve=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Approve</a>
                    <?php endif; ?>

                    <!-- Delete Event Button -->
                    <a href="manage_events.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JavaScript and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>