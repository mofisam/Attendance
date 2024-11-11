<?php
session_start();
include '../includes/db_connect.php';

$event_id = intval($_GET['event_id']); // Event ID passed in the URL
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

// Handle the form submission for manual registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Insert the new attendee into attendees table
    $query_insert_attendee = "INSERT INTO attendees (event_id, name, email, phone) VALUES (?, ?, ?, ?)";
    $stmt_insert_attendee = $conn->prepare($query_insert_attendee);
    $stmt_insert_attendee->bind_param("isss", $event_id, $name, $email, $phone);
    
    if ($stmt_insert_attendee->execute()) {
        // Retrieve the new attendee ID
        $attendee_id = $stmt_insert_attendee->insert_id;
        
        // Insert a new record in the attendance table and mark as checked in
        $query_insert_attendance = "INSERT INTO attendance (event_id, user_id, checked_in, marked_by, created_at) VALUES (?, ?, 1, ?, NOW())";
        $stmt_insert_attendance = $conn->prepare($query_insert_attendance);
        $stmt_insert_attendance->bind_param("iii", $event_id, $attendee_id, $user_id);
        
        if ($stmt_insert_attendance->execute()) {
            $success_message = "Attendee registered and checked in successfully!";
        } else {
            $error_message = "Error updating attendance. Please try again.";
        }
    } else {
        $error_message = "Error registering attendee. Please try again.";
    }
}

// Fetch attendees with their attendance status for this event
$query_attendees = "
    SELECT a.id, a.name, a.email, att.checked_in 
    FROM attendees a
    LEFT JOIN attendance att ON a.id = att.user_id AND att.event_id = ?
    WHERE a.event_id = ?";
$stmt_attendees = $conn->prepare($query_attendees);
$stmt_attendees->bind_param("ii", $event_id, $event_id);
$stmt_attendees->execute();
$result_attendees = $stmt_attendees->get_result();
?>

<?php include 'includes/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual Registration for <?php echo htmlspecialchars($event['event_name']); ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .registration-form {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
        }
        .registered-attendees {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1>Manual Registration for <?php echo htmlspecialchars($event['event_name']); ?></h1>
    <p>Date: <?php echo date("F j, Y", strtotime($event['event_date'])); ?></p>

    <?php if (!empty($success_message)) echo "<div class='alert alert-success'>$success_message</div>"; ?>
    <?php if (!empty($error_message)) echo "<div class='alert alert-danger'>$error_message</div>"; ?>

    <!-- Manual Registration Form -->
    <div class="registration-form">
        <h3>Register Attendee</h3>
        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" class="form-control" id="phone" name="phone">
            </div>
            <button type="submit" class="btn btn-primary">Register and Check In</button>
        </form>
    </div>

    <!-- Registered Attendees List -->
    <div class="registered-attendees">
        <h3>Registered Attendees</h3>
        <ul class="list-group">
            <?php if ($result_attendees->num_rows > 0): ?>
                <?php while ($attendee = $result_attendees->fetch_assoc()): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo htmlspecialchars($attendee['name']) . ' (' . htmlspecialchars($attendee['email']) . ')'; ?>
                        <span class="badge badge-<?php echo $attendee['checked_in'] ? 'success' : 'secondary'; ?>">
                            <?php echo $attendee['checked_in'] ? 'Checked In' : 'Not Checked In'; ?>
                        </span>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <li class="list-group-item">No attendees have been registered yet.</li>
            <?php endif; ?>
        </ul>
    </div>
</div>
</body>
</html>
