<!-- header.php -->

<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'checker') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checker Dashboard</title>
    <!-- Bootstrap CSS for styling -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
         /* Footer and header specific styles */
        .navbar-custom {
            background-color: #343a40;
        }
        .navbar-custom .navbar-brand, .navbar-custom .nav-link {
            color: #ffffff;
        }
        .navbar-custom .nav-link:hover {
            color: #ddd;
        }
        .footer {
            background-color: #343a40;
            color: #ffffff;
            padding-top: 20px;
        }

        .footer a:hover {
            text-decoration: none;
        }
        /*dashboard*/
        .dashboard-container {
            padding: 20px;
            margin-top: 20px;
        }
        .event-card, .profile-card {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);    
        }     
        .event-card, .profile-card {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom">
    <a class="navbar-brand" href="checker_dashboard.php">Event Checker</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"><i class="fas fa-bars text-light"></i></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="event_list.php">Events List</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="Edit_profile.php">Edit Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger" href="../logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>
