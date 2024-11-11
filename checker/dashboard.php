<?php
session_start();
include '../includes/db_connect.php';

// Fetch the current user's ID
$user_id = $_SESSION['user_id'];

// Query to fetch events assigned to the checker with total registered attendees
$query_events = "
    SELECT e.id, e.event_name, e.event_date, e.description,
           (SELECT COUNT(*) FROM attendees WHERE event_id = e.id) AS total_registered,
           (SELECT COUNT(*) FROM attendance WHERE event_id = e.id AND checked_in = 1) AS checked_in_count
    FROM events AS e
    JOIN event_checkers AS ec ON e.id = ec.event_id 
    WHERE ec.checker_id = ?";
$stmt = $conn->prepare($query_events);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

?>

<?php include 'includes/header.php'; ?>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<body>
<div class="container dashboard-container">
    <h1 class="mb-4">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1>

    <!-- Profile Section -->
    <div class="profile-card">
        <h2>Your Profile</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
        <a href="profile.php" class="btn btn-secondary">Edit Profile</a>
    </div>
    <!-- Charts Row -->
    <div class="row">
        <div class="col-md-6 event-card">
            <div class="chart-container">
                <!-- Event Attendance Chart -->
                <h3>Event Attendance Overview</h3>
                <canvas id="eventAttendanceChart" ></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container event-card">
                <!-- Check-In Status Chart -->
                <h3>Check-In Status</h3>
                <canvas id="checkInStatusChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <h2>Your Assigned Events</h2>
    <?php if (count($events) > 0): ?>
        <div class="row">
            <?php foreach ($events as $event): ?>
                <div class="col-md-6">
                    <div class="event-card">
                        <h4><?php echo htmlspecialchars($event['event_name']); ?></h4>
                        <p><strong>Date:</strong> <?php echo date("F j, Y", strtotime($event['event_date'])); ?></p>
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($event['description']); ?></p>
                        <p><strong>Total Registered:</strong> <?php echo $event['total_registered']; ?></p>
                        <a href="check_attendees.php?event_id=<?php echo $event['id']; ?>" class="btn btn-primary">
                            Manage Attendance
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No events have been assigned to you.</div>
    <?php endif; ?>
    
</div>    
<?php include 'includes/footer.php'; ?>

<script>
// Prepare data for Event Attendance Chart
const eventNames = <?php echo json_encode(array_column($events, 'event_name')); ?>;
const totalRegistered = <?php echo json_encode(array_column($events, 'total_registered')); ?>;

// Render Event Attendance Chart
const ctxEvent = document.getElementById('eventAttendanceChart').getContext('2d');
new Chart(ctxEvent, {
    type: 'bar',
    data: {
        labels: eventNames,
        datasets: [{
            label: 'Total Registered',
            data: totalRegistered,
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// Prepare data for Check-In Status Chart
const checkedInCounts = <?php echo json_encode(array_column($events, 'checked_in_count')); ?>;
const notCheckedInCounts = totalRegistered.map((total, i) => total - checkedInCounts[i]);

// Render Check-In Status Chart
const ctxCheckIn = document.getElementById('checkInStatusChart').getContext('2d');
new Chart(ctxCheckIn, {
    type: 'bar',
    data: {
        labels: ['Checked In', 'Not Checked In'],
        datasets: [{
            data: [
                checkedInCounts.reduce((a, b) => a + b, 0),
                notCheckedInCounts.reduce((a, b) => a + b, 0)
            ], 
            backgroundColor: ['rgba(75, 192, 192, 0.6)', 'rgba(255, 99, 132, 0.6)']
        }]
    },
    options: {
        responsive: true
    }
});
</script>

</body>
</html>
