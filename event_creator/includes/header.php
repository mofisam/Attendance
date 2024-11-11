<!-- header.php -->

<?php
session_start();
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous">
    <style>
        .navbar-custom {
            background-color: #343a40;
        }
        .navbar-custom .navbar-brand, .navbar-custom .nav-link {
            color: #ffffff;
        }
        .navbar-custom .nav-link:hover {
            color: #ddd;
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
                <a class="nav-link" href="checker_dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="assigned_events.php">Assigned Events</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="check_attendance.php">Check Attendees</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="profile.php">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger" href="../logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>
