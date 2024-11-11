<?php
session_start();

include '../includes/db_connect.php';

$event_id = intval($_GET['event_id']); // Event ID from the URL
$user_id = $_SESSION['user_id'];

// Verify that the checker has access to this event
$query_event = "SELECT event_name, event_date FROM events WHERE id = ? AND id IN (SELECT event_id FROM event_checkers WHERE checker_id = ?)";
$stmt_event = $conn->prepare($query_event);
$stmt_event->bind_param("ii", $event_id, $user_id);
$stmt_event->execute();
$result_event = $stmt_event->get_result();

if ($result_event->num_rows == 0) {
    echo "Event not found or you are not authorized to manage this event.";
    exit();
}

$event = $result_event->fetch_assoc();

$search_result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['attendee_id'])) {
    $attendee_id = intval($_POST['attendee_id']);

    // Check if the attendee is already checked in
    $query_check_attendance = "SELECT checked_in FROM attendance WHERE event_id = ? AND user_id = ?";
    $stmt_check_attendance = $conn->prepare($query_check_attendance);
    $stmt_check_attendance->bind_param("ii", $event_id, $attendee_id);
    $stmt_check_attendance->execute();
    $result_check_attendance = $stmt_check_attendance->get_result();

    if ($result_check_attendance->num_rows > 0) {
        $query_update_attendance = "UPDATE attendance SET checked_in = 1, marked_by = ?, created_at = NOW() WHERE event_id = ? AND user_id = ?";
        $stmt_update_attendance = $conn->prepare($query_update_attendance);
        $stmt_update_attendance->bind_param("iii", $user_id, $event_id, $attendee_id);
        $stmt_update_attendance->execute();
    } else {
        $query_insert_attendance = "INSERT INTO attendance (event_id, user_id, checked_in, marked_by, created_at) VALUES (?, ?, 1, ?, NOW())";
        $stmt_insert_attendance = $conn->prepare($query_insert_attendance);
        $stmt_insert_attendance->bind_param("iii", $event_id, $attendee_id, $user_id);
        $stmt_insert_attendance->execute();
    }

    $success_message = "Attendee checked in successfully!";
}
?>

<?php include 'includes/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Attendees for <?php echo htmlspecialchars($event['event_name']); ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        .check-in-form {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
        }
        .dropdown-search {
            position: relative;
            width: 100%;
        }
        .dropdown-menu {
            width: 100%;
            max-height: 150px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1>Check Attendees for <?php echo htmlspecialchars($event['event_name']); ?></h1>
    <p>Date: <?php echo date("F j, Y", strtotime($event['event_date'])); ?></p>

    <?php if (!empty($success_message)) echo "<div class='alert alert-success'>$success_message</div>"; ?>

    <!-- Form with Dropdown Search for Attendee Check-in -->
    <div class="check-in-form">
        <h3>Search and Check-in Attendee</h3>
        <form method="POST" action="">
            <div class="form-group">
                <label for="attendee_search">Name or Email</label>
                <div class="dropdown-search">
                    <input type="text" class="form-control" id="attendee_search" name="attendee_search" autocomplete="off" placeholder="Enter name or email" required>
                    <ul class="dropdown-menu" id="attendee_list"></ul>
                </div>
            </div>
            <input type="hidden" name="attendee_id" id="attendee_id">
            <button type="submit" class="btn btn-primary">Check In</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Function to search attendees as you type
        $('#attendee_search').on('keyup', function() {
            let searchQuery = $(this).val();
            if (searchQuery.length >= 2) {
                $.ajax({
                    url: "fetch_attendees.php",
                    method: "POST",
                    data: {query: searchQuery, event_id: <?php echo $event_id; ?>},
                    success: function(data) {
                        $('#attendee_list').html(data);
                        $('#attendee_list').show();
                    }
                });
            } else {
                $('#attendee_list').hide();
            }
        });

        // Function to select attendee from dropdown and populate hidden field
        $(document).on('click', '.dropdown-item', function() {
            let attendeeId = $(this).data('id');
            let attendeeName = $(this).text();
            $('#attendee_search').val(attendeeName);
            $('#attendee_id').val(attendeeId);
            $('#attendee_list').hide();
        });
    });
</script>

</body>
</html>
