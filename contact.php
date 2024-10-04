<?php
// Form Submission Logic
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    // Validate form data
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'Please fill out all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Email sending logic (this will send the email to the site admin)
        $to = 'admin@example.com'; // Admin email address
        $headers = "From: $name <$email>\r\n";
        $headers .= "Reply-To: $email\r\n";
        $full_message = "Name: $name\nEmail: $email\n\n$message";

        if (mail($to, $subject, $full_message, $headers)) {
            $success = 'Your message has been sent successfully!';
        } else {
            $error = 'There was an error sending your message. Please try again later.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/custom.css"> <!-- Custom CSS -->

    <style>
        body {
            background-color: #f7f9fc;
            font-family: 'Roboto', sans-serif;
        }

        .contact-container {
            padding: 60px 0;
        }

        .contact-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .contact-header h1 {
            font-size: 2.5rem;
            color: #007bff;
        }

        .form-group label {
            font-weight: 500;
            color: #333;
        }

        .form-control {
            border-radius: 10px;
            box-shadow: none;
            border-color: #007bff;
        }

        .form-control:focus {
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .btn-primary {
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 25px;
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<!-- Header -->
<?php include "includes/header.php"; ?> 
<div class="container contact-container">
    <div class="contact-header">
        <h1>Contact Us</h1>
        <p>If you have any questions, feel free to reach out to us. We're here to help!</p>
    </div>

    <!-- Display Success or Error Messages -->
    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <?php echo $success; ?>
        </div>
    <?php elseif (!empty($error)): ?>
        <div class="alert alert-danger">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <!-- Contact Form -->
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form action="contact.php" method="POST">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>" placeholder="Your Name">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" placeholder="Your Email">
                </div>

                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" class="form-control" id="subject" name="subject" value="<?php echo isset($_POST['subject']) ? $_POST['subject'] : ''; ?>" placeholder="Subject">
                </div>

                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea class="form-control" id="message" name="message" rows="5" placeholder="Your Message"><?php echo isset($_POST['message']) ? $_POST['message'] : ''; ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Send Message</button>
            </form>
        </div>
    </div>
</div>
<!-- Footer -->
<?php include "includes/footer.php"; ?> 
<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
