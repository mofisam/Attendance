<?php
include '../includes/auth.php'; // Ensure the admin is logged in
require '../includes/db_connect.php'; // Database connection

// Handle delete request
if (isset($_GET['delete'])) {
    $user_id = intval($_GET['delete']);
    $query = "DELETE FROM users WHERE id = $user_id";
    mysqli_query($conn, $query);
    header('Location: manage_users.php');
    exit();
}

// Handle update role request
if (isset($_POST['update_role'])) {
    $user_id = intval($_POST['user_id']);
    $new_role = mysqli_real_escape_string($conn, $_POST['role']);
    $query = "UPDATE users SET role = '$new_role' WHERE id = $user_id";
    mysqli_query($conn, $query);
    header('Location: manage_users.php');
    exit();
}

// Fetch all users
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .btn-edit {
            color: white;
            background-color: #007bff;
        }
        .btn-delete {
            color: white;
            background-color: #dc3545;
        }
    </style>
</head>
<body>
<?php include "../includes/header.php"; ?> 
<div class="container">
    <h1 class="mb-4">Manage Users</h1>

    <!-- Display Users in a Table -->
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo ucfirst($row['role']); ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td>
                    <!-- Edit User Role -->
                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editUserModal<?php echo $row['id']; ?>">
                        Edit Role
                    </button>

                    <!-- Delete User -->
                    <a href="manage_users.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>

                    <!-- Edit User Modal -->
                    <div class="modal fade" id="editUserModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editUserModalLabel">Edit Role for <?php echo $row['name']; ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="POST" action="manage_users.php">
                                    <div class="modal-body">
                                        <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                        <div class="form-group">
                                            <label for="role">Role</label>
                                            <select name="role" class="form-control">
                                                <option value="admin" <?php if ($row['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                                                <option value="event_creator" <?php if ($row['role'] == 'event_creator') echo 'selected'; ?>>Event Creator</option>
                                                <option value="checker" <?php if ($row['role'] == 'checker') echo 'selected'; ?>>Checker</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" name="update_role" class="btn btn-primary">Update Role</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<!-- Footer -->
<?php include "../includes/footer.php"; ?> 
<!-- Bootstrap JavaScript and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
