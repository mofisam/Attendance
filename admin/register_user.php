<?php
include 'includes/db_connect.php'; // Include database connection
include 'includes/auth.php'; // Authentication check (admin only)

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $role = $_POST['role']; // Role could be 'admin', 'event_creator', or 'checker'

    // Insert user into the database
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if ($stmt->execute()) {
        $message = "User registered successfully.";
    } else {
        $error = "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register User</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/custom.css"> <!-- Link to custom CSS file -->
    
    <style>
        /* Inline custom styling for the form */
        body {
            background-color: #f4f7fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .form-control {
            border-radius: 25px;
        }
        .btn-primary {
            border-radius: 25px;
            padding: 10px 20px;
        }
        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .form-header h3 {
            color: #007bff;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-header">
        <h3>Register New User</h3>
    </div>

    <!-- Show success or error message -->
    <?php if (isset($message)): ?>
        <div class="alert alert-success" role="alert">
            <?php echo $message; ?>
        </div>
    <?php elseif (isset($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="register_user.php">
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter full name" required>
        </div>

        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select class="form-control" id="role" name="role" required>
                <option value="">Select role</option>
                <option value="admin">Admin</option>
                <option value="event_creator">Event Creator</option>
                <option value="checker">Checker</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Register User</button>
    </form>
</div>

<!-- Footer -->
<?php include "includes/footer.php"; ?>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
