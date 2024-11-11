<?php
session_start();
include '../includes/auth.php';
include '../includes/db_connect.php';

// Fetch current Event Creator's ID
$user_id = $_SESSION['user_id'];

// Summary data queries
$total_events_query = "SELECT COUNT(*) AS total_events FROM events WHERE created_by = ?";
$total_attendees_query = "
    SELECT COUNT(*) AS total_attendees 
    FROM attendees 
    JOIN events ON attendees.event_id = events.id 
    WHERE events.created_by = ?";
$total_checked_in_query = "
    SELECT COUNT(*) AS checked_in 
    FROM attendance 
    JOIN events ON attendance.event_id = events.id 
    WHERE events.created_by = ? AND attendance.checked_in = 1";

$stmt1 = $conn->prepare($total_events_query);
$stmt1->bind_param("i", $user_id);
$stmt1->execute();
$total_events = $stmt1->get_result()->fetch_assoc()['total_events'];

$stmt2 = $conn->prepare($total_attendees_query);
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$total_attendees = $stmt2->get_result()->fetch_assoc()['total_attendees'];

$stmt3 = $conn->prepare($total_checked_in_query);
$stmt3->bind_param("i", $user_id);
$stmt3->execute();
$total_checked_in = $stmt3->get_result()->fetch_assoc()['checked_in'];

// Event attendance chart data
$event_attendance_query = "
    SELECT e.event_name, COUNT(a.id) AS total_registered, 
           SUM(IF(at.checked_in = 1, 1, 0)) AS total_checked_in
    FROM events AS e
    LEFT JOIN attendees AS a ON e.id = a.event_id
    LEFT JOIN attendance AS at ON a.id = at.user_id AND at.event_id = e.id
    WHERE e.created_by = ?
    GROUP BY e.id";
$stmt4 = $conn->prepare($event_attendance_query);
$stmt4->bind_param("i", $user_id);
$stmt4->execute();
$result = $stmt4->get_result();

$event_names = [];
$registered_counts = [];
$checked_in_counts = [];
while ($row = $result->fetch_assoc()) {
    $event_names[] = $row['event_name'];
    $registered_counts[] = $row['total_registered'];
    $checked_in_counts[] = $row['total_checked_in'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Creator Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-container {
            padding: 30px;
            background-color: #f4f6f9;
        }
        .summary-card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .chart-container {
            margin: 20px 0;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
    </style>
</head>
<body>
<div class="container dashboard-container">
    <h2 class="text-center mb-5">Event Creator Dashboard</h2>
    
    <!-- Summary Cards -->
    <div class="row">
        <div class="col-md-4">
            <div class="summary-card">
                <h5>Total Events</h5>
                <p><?php echo $total_events; ?></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="summary-card">
                <h5>Total Registered Attendees</h5>
                <p><?php echo $total_attendees; ?></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="summary-card">
                <h5>Total Checked-In Attendees</h5>
                <p><?php echo $total_checked_in; ?></p>
            </div>
        </div>
    </div>
    
    <!-- Event Attendance Chart -->
    <div class="chart-container">
        <h5 class="text-center">Event Performance Overview</h5>
        <canvas id="eventPerformanceChart"></canvas>
    </div>
</div>

<script>
    // Event Performance Chart
    const eventPerformanceChartCtx = document.getElementById('eventPerformanceChart').getContext('2d');
    new Chart(eventPerformanceChartCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($event_names); ?>,
            datasets: [
                {
                    label: 'Registered',
                    data: <?php echo json_encode($registered_counts); ?>,
                    backgroundColor: '#42A5F5'
                },
                {
                    label: 'Checked In',
                    data: <?php echo json_encode($checked_in_counts); ?>,
                    backgroundColor: '#66BB6A'
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
<?php include 'includes/footer.php'; ?>

</body>
</html>
