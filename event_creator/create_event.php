<?php
include '../includes/auth.php'; // Ensure the user is logged in (could be event creator)
require '../includes/db_connect.php'; // Database connection

// Initialize variables for form fields
$event_name = $description = $event_date = $event_time = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate inputs
    $event_name = mysqli_real_escape_string($conn, $_POST['event_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $event_date = mysqli_real_escape_string($conn, $_POST['event_date']);
    $event_time = mysqli_real_escape_string($conn, $_POST['event_time']);
    
    // Basic validation
    if (empty($event_name)) {
        $errors['event_name'] = 'Event name is required';
    }
    if (empty($description)) {
        $errors['description'] = 'Event description is required';
    }
    if (empty($event_date)) {
        $errors['event_date'] = 'Event date is required';
    }
    if (empty($event_time)) {
        $errors['event_time'] = 'Event time is required';
    }

    // If no errors, insert into the database
    if (empty($errors)) {
        $created_by = $_SESSION['user_id']; // Assuming user is logged in and creator ID is stored in session

        $query = "INSERT INTO events (event_name, description, event_date, event_time, created_by, is_approved) 
                  VALUES ('$event_name', '$description', '$event_date', '$event_time', $created_by, 0)";
        
        if (mysqli_query($conn, $query)) {
            // Redirect to a success page or back to event management
            header('Location: event_success.php');
            exit();
        } else {
            $errors['database'] = 'Error creating event: ' . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Event</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
            max-width: 600px;
        }
        .card {
            padding: 20px;
        }
        .error {
            color: red;
            font-size: 0.9em;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h3 class="card-title text-center">Create New Event</h3>
        <form method="POST" action="create_event.php">
            <!-- Event Name -->
            <div class="form-group">
                <label for="event_name">Event Name</label>
                <input type="text" class="form-control" id="event_name" name="event_name" value="<?php echo htmlspecialchars($event_name); ?>">
                <?php if (isset($errors['event_name'])): ?>
                    <div class="error"><?php echo $errors['event_name']; ?></div>
                <?php endif; ?>
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description">Event Description</label>
                <textarea class="form-control" id="description" name="description" rows="4"><?php echo htmlspecialchars($description); ?></textarea>
                <?php if (isset($errors['description'])): ?>
                    <div class="error"><?php echo $errors['description']; ?></div>
                <?php endif; ?>
            </div>

            <!-- Event Date -->
            <div class="form-group">
                <label for="event_date">Event Date</label>
                <input type="date" class="form-control" id="event_date" name="event_date" value="<?php echo htmlspecialchars($event_date); ?>">
                <?php if (isset($errors['event_date'])): ?>
                    <div class="error"><?php echo $errors['event_date']; ?></div>
                <?php endif; ?>
            </div>

            <!-- Event Time -->
            <div class="form-group">
                <label for="event_time">Event Time</label>
                <input type="time" class="form-control" id="event_time" name="event_time" value="<?php echo htmlspecialchars($event_time); ?>">
                <?php if (isset($errors['event_time'])): ?>
                    <div class="error"><?php echo $errors['event_time']; ?></div>
                <?php endif; ?>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Create Event</button>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap JavaScript and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>