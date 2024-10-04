<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/custom.css"> <!-- Custom CSS -->

    <style>
        body {
            background-color: #f7f9fc;
            font-family: 'Roboto', sans-serif;
        }
        .about-container {
            padding: 50px 0;
        }
        .about-header {
            text-align: center;
            margin-bottom: 50px;
        }
        .about-header h1 {
            font-size: 3rem;
            color: #007bff;
            margin-bottom: 10px;
        }
        .about-header p {
            font-size: 1.2rem;
            color: #555;
        }
        .about-content {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }
        .about-text {
            flex: 1;
            padding: 20px;
        }
        .about-image {
            flex: 1;
            text-align: center;
        }
        .about-image img {
            max-width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .about-text p {
            font-size: 1.1rem;
            color: #333;
            line-height: 1.6;
        }
        .about-values {
            background-color: #fff;
            padding: 50px 0;
            margin-top: 50px;
        }
        .values-header {
            text-align: center;
            margin-bottom: 50px;
        }
        .values-item {
            text-align: center;
            padding: 20px;
        }
        .values-item h4 {
            margin-top: 15px;
            color: #007bff;
        }
        .values-item p {
            color: #555;
        }
    </style>
</head>
<body>

<div class="container about-container">
    <!-- About Header -->
    <div class="about-header">
        <h1>About Us</h1>
        <p>Learn more about our mission, vision, and the people behind the scenes.</p>
    </div>

    <!-- About Content -->
    <div class="about-content">
        <div class="about-text">
            <h2>Our Mission</h2>
            <p>
                Our mission is to revolutionize how people manage and track attendance in events, providing a seamless 
                experience for both organizers and attendees. With advanced features, our platform allows you to 
                effortlessly track attendance, manage registrations, and ensure your events run smoothly.
            </p>
            <p>
                Whether it’s a small gathering or a large conference, we strive to offer an intuitive solution that fits 
                your needs, giving you the tools to focus on what really matters—engaging with your audience.
            </p>
        </div>

        <div class="about-image">
            <img src="assets/images/team_photo.jpeg" alt="Team Photo">
        </div>
    </div>
</div>

<!-- Our Values Section -->
<div class="about-values">
    <div class="container">
        <div class="values-header">
            <h2>Our Core Values</h2>
        </div>

        <div class="row">
            <div class="col-md-4 values-item">
                <img src="assets/images/value_innovation.svg" alt="Innovation Icon" width="80">
                <h4>Innovation</h4>
                <p>We embrace cutting-edge technology to create solutions that improve how you manage your events.</p>
            </div>
            <div class="col-md-4 values-item">
                <img src="assets/images/value_integrity.svg" alt="Integrity Icon" width="80">
                <h4>Integrity</h4>
                <p>We build trust through transparency and always deliver on our promises.</p>
            </div>
            <div class="col-md-4 values-item">
                <img src="assets/images/value_customer_focus.svg" alt="Customer Focus Icon" width="80">
                <h4>Customer Focus</h4>
                <p>We listen to our users' feedback and consistently enhance our product based on your needs.</p>
            </div>
        </div>
    </div>
</div>
<?php include "includes/footer.php" ?>
<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>