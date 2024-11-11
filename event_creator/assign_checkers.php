<?php
session_start();
include '../includes/auth.php'; // Ensure the user is authenticated as event creator
include '../includes/db_connect.php'; // Include database connection

// Get the event creator's ID from the session
$creator_id = $_SESSION['user_id'];

// Fetch all users who are checkers (can be from a specific team of the creator)
$query_checkers = "SELECT * FROM users WHERE role = 'checker' ";
$result_checkers = mysqli_query($conn, $query_checkers);

// Fetch events created by the event creator
$query_events = "SELECT * FROM events WHERE created_by = $creator_id ORDER BY created_at DESC";
$result_events = mysqli_query($conn, $query_events);

// Assign a checker to an event
if (isset($_POST['assign'])) {
    $event_id = intval($_POST['event_id']);
    $checker_id = intval($_POST['checker_id']);
    
    // Check if the checker is already assigned
    $query_check_existing = "SELECT * FROM event_checkers WHERE event_id = $event_id AND checker_id = $checker_id";
    $result_check_existing = mysqli_query($conn, $query_check_existing);

    if (mysqli_num_rows($result_check_existing) == 0) {
        $query_assign = "INSERT INTO event_checkers (event_id, checker_id) VALUES ($event_id, $checker_id)";
        mysqli_query($conn, $query_assign);
        $_SESSION['success'] = "Checker assigned successfully.";
    } else {
        $_SESSION['error'] = "This checker is already assigned to the event.";
    }
    header("Location: assign_checkers.php");
    exit();
}

// Remove a checker from an event
if (isset($_GET['remove'])) {
    $checker_id = intval($_GET['remove']);
    $event_id = intval($_GET['event_id']);
    
    $query_remove = "DELETE FROM event_checkers WHERE checker_id = $checker_id AND event_id = $event_id";
    mysqli_query($conn, $query_remove);
    
    $_SESSION['success'] = "Checker removed successfully.";
    header("Location: assign_checkers.php");
    exit();
}

// Fetch assigned checkers for each event
$query_assigned_checkers = "SELECT ec.*, u.name as checker_name, e.event_name FROM event_checkers ec
                            JOIN users u ON ec.checker_id = u.id
                            JOIN events e ON ec.event_id = e.id
                            WHERE e.created_by = $creator_id";
$result_assigned_checkers = mysqli_query($conn, $query_assigned_checkers);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Checkers</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
        }
        .card {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">Assign Checkers to Events</h2>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="post" action="assign_checkers.php">
                <div class="form-group">
                    <label for="event_id">Select Event:</label>
                    <select name="event_id" id="event_id" class="form-control" required>
                        <?php while ($event = mysqli_fetch_assoc($result_events)): ?>
                            <option value="<?php echo $event['id']; ?>"><?php echo $event['event_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="checker_id">Select Checker:</label>
                    <select name="checker_id" id="checker_id" class="form-control" required>
                        <?php while ($checker = mysqli_fetch_assoc($result_checkers)): ?>
                            <option value="<?php echo $checker['id']; ?>"><?php echo $checker['name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" name="assign" class="btn btn-primary">Assign Checker</button>
            </form>
        </div>
    </div>

    <h3 class="mb-4">Assigned Checkers</h3>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Checker Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($assigned_checker = mysqli_fetch_assoc($result_assigned_checkers)): ?>
                <tr>
                    <td><?php echo $assigned_checker['event_name']; ?></td>
                    <td><?php echo $assigned_checker['checker_name']; ?></td>
                    <td>
                        <a href="assign_checkers.php?remove=<?php echo $assigned_checker['checker_id']; ?>&event_id=<?php echo $assigned_checker['event_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to remove this checker?');">Remove</a>
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