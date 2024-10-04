<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/custom.css"> <!-- Custom CSS -->

    <style>
        body {
            background-color: #f7f9fc;
            font-family: 'Roboto', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            text-align: center;
        }

        .error-image {
            max-width: 400px;
            margin: 0 auto;
        }

        h1 {
            font-size: 100px;
            color: #007bff;
            font-weight: bold;
        }

        p {
            font-size: 18px;
            color: #555;
            margin-top: -20px;
        }

        .btn-primary {
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 25px;
            margin-top: 20px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Error Image -->
    <div class="error-image">
        <img src="assets/images/404_image.svg" alt="404 Not Found" class="img-fluid">
    </div>

    <!-- Error Message -->
    <h1>404</h1>
    <p>Oops! The page you're looking for doesn't exist.</p>

    <!-- Home Button -->
    <a href="index.php" class="btn btn-primary">Back to Home</a>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
