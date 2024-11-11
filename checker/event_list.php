<?php
session_start();
include '../includes/db_connect.php';

// Fetch the current user's ID
$user_id = $_SESSION['user_id'];

// Query to fetch events assigned to the checker
$query_events = "
    SELECT e.id, e.event_name, e.event_date, e.description, 
           (SELECT COUNT(*) FROM attendees WHERE event_id = e.id) AS total_registered
    FROM events AS e
    JOIN event_checkers AS ec ON e.id = ec.event_id 
    WHERE ec.checker_id = ?";
$stmt = $conn->prepare($query_events);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php include 'includes/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigned Events</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Your Assigned Events</h1>
    
    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Event Name</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Total Registered</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($event = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($event['event_name']); ?></td>
                            <td><?php echo date("F j, Y", strtotime($event['event_date'])); ?></td>
                            <td><?php echo htmlspecialchars($event['description']); ?></td>
                            <td><?php echo $event['total_registered']; ?></td>
                            <td>
                                <a href="check_attendees.php?event_id=<?php echo $event['id']; ?>" class="btn btn-primary btn-sm">
                                    Manage Attendance
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No events have been assigned to you.</div>
    <?php endif; ?>
</div>
</body>
</html>
