<?php
include '../includes/auth.php'; // Ensure the user is authenticated
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Created Successfully</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
            max-width: 600px;
        }
        .card {
            padding: 20px;
            text-align: center;
        }
        .success-icon {
            font-size: 5rem;
            color: #28a745;
        }
        .btn-custom {
            margin: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i> <!-- FontAwesome Success Icon -->
        </div>
        <h3 class="card-title mt-3">Event Created Successfully!</h3>
        <p class="card-text">Your event has been successfully created and is pending approval.</p>

        <a href="create_event.php" class="btn btn-primary btn-custom">Create Another Event</a>
        <a href="dashboard.php" class="btn btn-secondary btn-custom">Go to Dashboard</a>
    </div>
</div>

<!-- Font Awesome -->
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<!-- Bootstrap JavaScript -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
