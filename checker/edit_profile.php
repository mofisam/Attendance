<?php
session_start();
include '../includes/db_connect.php';

// Fetch the current user's ID and profile data
$user_id = $_SESSION['user_id'];

// Query to fetch current profile data
$query = "SELECT name, email FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle profile update and password change form submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    // Update Profile Information
    if (!empty($name) && !empty($email)) {
        $update_query = "UPDATE users SET name = ?, email = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ssi", $name, $email, $user_id);
        if ($update_stmt->execute()) {
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $success_message = "Profile updated successfully!";
        } else {
            $error_message = "Error updating profile. Please try again.";
        }
    } else {
        $error_message = "Please fill in all fields.";
    }

    // Update Password
    if (!empty($_POST['current_password']) && !empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Verify current password
        $password_query = "SELECT password FROM users WHERE id = ?";
        $password_stmt = $conn->prepare($password_query);
        $password_stmt->bind_param("i", $user_id);
        $password_stmt->execute();
        $password_result = $password_stmt->get_result();
        $user_data = $password_result->fetch_assoc();

        if (password_verify($current_password, $user_data['password'])) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_password_query = "UPDATE users SET password = ? WHERE id = ?";
                $update_password_stmt = $conn->prepare($update_password_query);
                $update_password_stmt->bind_param("si", $hashed_password, $user_id);

                if ($update_password_stmt->execute()) {
                    $success_message .= "<br>Password updated successfully!";
                } else {
                    $error_message = "Error updating password. Please try again.";
                }
            } else {
                $error_message = "New passwords do not match.";
            }
        } else {
            $error_message = "Current password is incorrect.";
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .profile-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
        }
        .form-header {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container profile-container">
    <h2 class="form-header">Edit Profile</h2>

    <!-- Display Success or Error Messages -->
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php elseif (!empty($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Profile Edit Form -->
    <form method="POST" action="">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <hr>

        <h5>Change Password</h5>
        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input type="password" class="form-control" id="current_password" name="current_password">
        </div>
        <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" class="form-control" id="new_password" name="new_password">
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
        </div>

        <button type="submit" class="btn btn-primary btn-block">Save Changes</button>
    </form>
    <a href="dashboard.php" class="btn btn-secondary btn-block mt-3">Back to Dashboard</a>
</div>
</body>
</html>
