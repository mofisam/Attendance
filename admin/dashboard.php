<?php
session_start();
include '../includes/auth.php';
include '../includes/db_connect.php';

// Fetch summary data
$total_events_query = "SELECT COUNT(*) AS total_events FROM events";
$total_attendees_query = "SELECT COUNT(*) AS total_attendees FROM attendees";
$total_checked_in_query = "SELECT COUNT(*) AS checked_in FROM attendance WHERE checked_in = 1";

$total_events = $conn->query($total_events_query)->fetch_assoc()['total_events'];
$total_attendees = $conn->query($total_attendees_query)->fetch_assoc()['total_attendees'];
$total_checked_in = $conn->query($total_checked_in_query)->fetch_assoc()['checked_in'];

// Event attendance chart data
$event_attendance_query = "
    SELECT e.event_name, COUNT(a.id) AS total_registered,
           SUM(IF(at.checked_in = 1, 1, 0)) AS total_checked_in
    FROM events AS e
    LEFT JOIN attendees AS a ON e.id = a.event_id
    LEFT JOIN attendance AS at ON a.id = at.user_id AND at.event_id = e.id
    GROUP BY e.id";
$event_attendance_result = $conn->query($event_attendance_query);

// Prepare data for chart
$event_names = [];
$registered_counts = [];
$checked_in_counts = [];
while ($row = $event_attendance_result->fetch_assoc()) {
    $event_names[] = $row['event_name'];
    $registered_counts[] = $row['total_registered'];
    $checked_in_counts[] = $row['total_checked_in'];
}

// User role distribution - store in arrays for chart
$role_labels = [];
$role_counts = [];
$role_distribution_query = "SELECT role, COUNT(*) AS count FROM users GROUP BY role";
$role_distribution_result = $conn->query($role_distribution_query);
while ($row = $role_distribution_result->fetch_assoc()) {
    $role_labels[] = $row['role'];
    $role_counts[] = $row['count'];
}

// Event status based on `is_approved`
$event_status_query = "
    SELECT 
        SUM(CASE WHEN is_approved = 1 THEN 1 ELSE 0 END) AS approved,
        SUM(CASE WHEN is_approved = 0 THEN 1 ELSE 0 END) AS pending
    FROM events";
$event_status_result = $conn->query($event_status_query)->fetch_assoc();

// Top 5 event creators
$top_creators_query = "
    SELECT u.name, COUNT(e.id) AS event_count 
    FROM users AS u 
    JOIN events AS e ON u.id = e.created_by 
    GROUP BY u.id 
    ORDER BY event_count DESC 
    LIMIT 5";
$top_creators_result = $conn->query($top_creators_query);

// Recent events
$recent_events_query = "SELECT event_name, event_date, is_approved FROM events ORDER BY event_date DESC LIMIT 5";
$recent_events_result = $conn->query($recent_events_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-container {
            padding: 30px;
            background-color: #f4f6f9;
        }
        .summary-card, .chart-container {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container dashboard-container">
    <h2 class="text-center mb-5">Admin Dashboard</h2>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="summary-card">
                <h5>Total Events</h5>
                <p><?php echo $total_events; ?></p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card">
                <h5>Total Registered Attendees</h5>
                <p><?php echo $total_attendees; ?></p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card">
                <h5>Total Checked-In</h5>
                <p><?php echo $total_checked_in; ?></p>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-md-6">
            <div class="chart-container">
                <h5 class="text-center">User Role Distribution</h5>
                <canvas id="roleDistributionChart"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container">
                <h5 class="text-center">Event Approval Status</h5>
                <canvas id="eventStatusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Additional Data -->
    <div class="row">
        <div class="col-md-6">
            <div class="chart-container">
                <h5 class="text-center">Top 5 Event Creators</h5>
                <ul>
                    <?php while ($creator = $top_creators_result->fetch_assoc()): ?>
                        <li><?php echo htmlspecialchars($creator['name']); ?> - <?php echo $creator['event_count']; ?> Events</li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container">
                <h5 class="text-center">Recent Events</h5>
                <ul>
                    <?php while ($event = $recent_events_result->fetch_assoc()): ?>
                        <li><?php echo htmlspecialchars($event['event_name']); ?> - <?php echo $event['is_approved'] ? "Approved" : "Pending"; ?> on <?php echo date("F j, Y", strtotime($event['event_date'])); ?></li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    // User Role Distribution Chart
    const roleData = {
        labels: <?php echo json_encode($role_labels); ?>,
        datasets: [{
            data: <?php echo json_encode($role_counts); ?>,
            backgroundColor: ['#42A5F5', '#66BB6A', '#FFCA28', '#FF7043']
        }]
    };
    new Chart(document.getElementById('roleDistributionChart').getContext('2d'), {
        type: 'pie',
        data: roleData,
        options: { responsive: true }
    });

    // Event Approval Status Chart
    const eventStatusData = {
        labels: ["Approved", "Pending"],
        datasets: [{
            data: [<?php echo $event_status_result['approved']; ?>, <?php echo $event_status_result['pending']; ?>],
            backgroundColor: ['#4CAF50', '#FFC107']
        }]
    };
    new Chart(document.getElementById('eventStatusChart').getContext('2d'), {
        type: 'doughnut',
        data: eventStatusData,
        options: { responsive: true }
    });
</script>
<?php include 'includes/footer.php'; ?>
</body>
</html>
