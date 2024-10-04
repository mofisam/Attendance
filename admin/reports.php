<?php
include '../includes/auth.php'; // Ensure the admin is logged in
require '../includes/db_connect.php'; // Database connection

// Fetch user roles distribution
$query = "SELECT role, COUNT(*) as total FROM users GROUP BY role";
$user_roles_result = mysqli_query($conn, $query);

// Fetch events approved vs pending
$query = "SELECT is_approved, COUNT(*) as total FROM events GROUP BY is_approved";
$event_status_result = mysqli_query($conn, $query);

// Fetch top 5 event creators
$query = "
    SELECT users.name, COUNT(events.id) as event_count 
    FROM events 
    JOIN users ON events.created_by = users.id 
    GROUP BY users.name 
    ORDER BY event_count DESC 
    LIMIT 5";
$top_creators_result = mysqli_query($conn, $query);

// Fetch recent events
$query = "SELECT event_name, created_at FROM events ORDER BY created_at DESC LIMIT 5";
$recent_events_result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Reports</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .container {
            margin-top: 50px;
        }
        .card {
            margin-bottom: 30px;
        }
        .chart-container {
            position: relative;
            height: 300px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="mb-4">Admin Dashboard - Reports</h1>

    <div class="row">
        <!-- User Roles Distribution Card -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>User Roles Distribution</h4>
                </div>
                <div class="card-body">
                    <canvas id="userRolesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Event Status (Approved vs Pending) Card -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Event Status (Approved vs Pending)</h4>
                </div>
                <div class="card-body">
                    <canvas id="eventStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Event Creators Table -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Top 5 Event Creators</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Number of Events</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($top_creators_result)) { ?>
                            <tr>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['event_count']; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Events Table -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Recent Events</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Event Name</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($recent_events_result)) { ?>
                            <tr>
                                <td><?php echo $row['event_name']; ?></td>
                                <td><?php echo $row['created_at']; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Scripts -->
<script>
// User Roles Chart Data
var ctx1 = document.getElementById('userRolesChart').getContext('2d');
var userRolesChart = new Chart(ctx1, {
    type: 'pie',
    data: {
        labels: [
            <?php 
            while ($row = mysqli_fetch_assoc($user_roles_result)) {
                echo '"' . ucfirst($row['role']) . '", ';
            } 
            ?>
        ],
        datasets: [{
            data: [
                <?php 
                mysqli_data_seek($user_roles_result, 0); // Reset pointer to first result
                while ($row = mysqli_fetch_assoc($user_roles_result)) {
                    echo $row['total'] . ', ';
                } 
                ?>
            ],
            backgroundColor: ['#007bff', '#28a745', '#dc3545'],
        }]
    },
    options: {
        responsive: true
    }
});

// Event Status Chart Data
var ctx2 = document.getElementById('eventStatusChart').getContext('2d');
var eventStatusChart = new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: ['Pending', 'Approved'],
        datasets: [{
            label: '# of Events',
            data: [
                <?php 
                while ($row = mysqli_fetch_assoc($event_status_result)) {
                    echo $row['is_approved'] ? $row['total'] . ', ' : '0, ';
                }
                mysqli_data_seek($event_status_result, 0);
                while ($row = mysqli_fetch_assoc($event_status_result)) {
                    echo !$row['is_approved'] ? $row['total'] . ', ' : '0, ';
                }
                ?>
            ],
            backgroundColor: ['#ffc107', '#28a745'],
        }]
    },
    options: {
        responsive: true
    }
});
</script>

<!-- Bootstrap JavaScript and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>