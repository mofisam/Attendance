<?php
session_start();
require '../includes/db_connect.php'; // Adjust the path as needed
$event_id = $_GET['event_id'] ?? null;

// Redirect if no event ID is provided
if (!$event_id) {
    header('Location: create_event.php'); // Adjust path to the events listing page
    exit;
}

// Fetch event details (optional, if you want to display the event name and details)
$event_query = "SELECT * FROM events WHERE id = ?";
$stmt = $conn->prepare($event_query);
$stmt->bind_param('i', $event_id);
$stmt->execute();
$event_result = $stmt->get_result();
$event = $event_result->fetch_assoc();

// Fetch attendees for this event
$attendees_query = "SELECT * FROM attendees WHERE event_id = ?";
$stmt = $conn->prepare($attendees_query);
$stmt->bind_param('i', $event_id);
$stmt->execute();
$attendees_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance - <?php echo htmlspecialchars($event['name']); ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container { margin-top: 30px; }
        h1 { color: #5a5a5a; }
        table { margin-top: 20px; }
        .search-bar { max-width: 300px; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="container">
    <h1>Attendance for Event: <?php echo htmlspecialchars($event['name']); ?></h1>
    <p>Date: <?php echo htmlspecialchars($event['event_date']); ?></p>
    
    <!-- Search Bar -->
    <div class="input-group search-bar">
        <input type="text" id="searchInput" class="form-control" placeholder="Search attendees...">
    </div>

    <!-- Attendees Table -->
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Age</th>
                <th>Sex</th>
                <th>Check-In Status</th>
            </tr>
        </thead>
        <tbody id="attendeeTable">
            <?php if ($attendees_result->num_rows > 0): ?>
                <?php $counter = 1; ?>
                <?php while ($row = $attendees_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $counter++; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['age']); ?></td>
                        <td><?php echo htmlspecialchars($row['sex']); ?></td>
                        <td><?php echo $row['checked_in'] ? 'Checked In' : 'Not Checked In'; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No attendees found for this event.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    // Search filter functionality
    $(document).ready(function(){
        $("#searchInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#attendeeTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>

</body>
</html>

<?php
// Close database connections
$stmt->close();
$conn->close();
?>
