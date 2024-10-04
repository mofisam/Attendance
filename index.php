<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Attendance System</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/custom.css"> <!-- Custom CSS -->

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f7f9fc;
        }

        .navbar {
            background-color: #007bff;
        }

        .navbar a {
            color: white;
        }

        .hero-section {
            background: url('assets/images/hero-bg.jpg') no-repeat center center/cover;
            color: white;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
        }

        .hero-section .content {
            background: rgba(0, 0, 0, 0.5);
            padding: 40px;
            border-radius: 10px;
        }

        .hero-section h1 {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        .hero-section p {
            font-size: 1.5rem;
            margin-bottom: 30px;
        }

        .hero-section .btn {
            padding: 15px 30px;
            font-size: 1.2rem;
            border-radius: 30px;
        }

        .features-section {
            padding: 60px 0;
            text-align: center;
        }

        .features-section h2 {
            color: #007bff;
            margin-bottom: 50px;
        }

        .features-section .feature-box {
            margin-bottom: 40px;
        }

        .feature-box img {
            max-width: 100px;
            margin-bottom: 20px;
        }

        .feature-box h4 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .cta-section {
            background-color: #007bff;
            color: white;
            padding: 60px 0;
            text-align: center;
        }

        .cta-section h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .cta-section p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        .cta-section .btn {
            padding: 15px 30px;
            font-size: 1.2rem;
            border-radius: 30px;
            background-color: #fff;
            color: #007bff;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="#">Attendance System</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="about.php">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="contact.php">Contact</a>
            </li>
        </ul>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
    <div class="content">
        <h1>Welcome to Our Attendance System</h1>
        <p>Effortless Attendance Tracking for Your Events and Teams</p>
        <a href="register.php" class="btn btn-primary">Get Started</a>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <h2>Our Features</h2>
        <div class="row">
            <div class="col-md-4 feature-box">
                <img src="assets/images/feature1.jpg" alt="Feature 1">
                <h4>Easy Attendance Management</h4>
                <p>Manage and track attendance effortlessly with our intuitive system.</p>
            </div>
            <div class="col-md-4 feature-box">
                <img src="assets/images/feature2.svg" alt="Feature 2">
                <h4>Real-Time Reporting</h4>
                <p>Get real-time data on attendee check-ins and other event metrics.</p>
            </div>
            <div class="col-md-4 feature-box">
                <img src="assets/images/feature3.svg" alt="Feature 3">
                <h4>Customizable Settings</h4>
                <p>Tailor the system to meet your event’s specific needs.</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="cta-section">
    <div class="container">
        <h2>Ready to Get Started?</h2>
        <p>Sign up now to simplify your event attendance process.</p>
        <a href="register.php" class="btn">Sign Up Today</a>
    </div>
</section>

<!-- Footer -->
<?php include "includes/footer.php"; ?> 

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>