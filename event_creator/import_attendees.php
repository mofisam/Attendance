<?php
session_start();
include '../includes/auth.php'; // Ensure the user is authenticated as event creator
include '../includes/db_connect.php'; // Include database connection

// Include PhpSpreadsheet for Excel handling
require '../vendor/autoload.php'; // Assuming PhpSpreadsheet is installed via Composer

use PhpOffice\PhpSpreadsheet\IOFactory;

// Handle file upload and processing
if (isset($_POST['upload'])) {
    $event_id = intval($_POST['event_id']);

    // File upload validation
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == 0) {
        $file = $_FILES['excel_file']['tmp_name'];
        
        // Load the uploaded Excel file
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        
        // Fetch all rows and columns from the Excel file
        $excel_data = [];
        for ($row = 1; $row <= $highestRow; $row++) {
            $row_data = [];
            foreach (range('A', $highestColumn) as $column) {
                $row_data[$column] = $sheet->getCell($column . $row)->getValue();
            }
            $excel_data[] = $row_data;
        }
        
        $_SESSION['excel_data'] = $excel_data; // Store data temporarily in session
        $_SESSION['event_id'] = $event_id;

        header('Location: import_attendees.php?step=2');
        exit();
    } else {
        $_SESSION['error'] = "Failed to upload the file.";
    }
}

// Handle column mapping and saving to the database
if (isset($_POST['save_mapping'])) {
    $selected_columns = $_POST['columns'];
    $excel_data = $_SESSION['excel_data'];
    $event_id = $_SESSION['event_id'];

    // Insert the mapped data into the attendees table
    foreach ($excel_data as $row => $row_data) {
        if ($row == 0) continue; // Skip the header row

        $name = !empty($selected_columns['name']) ? $row_data[$selected_columns['name']] : '';
        $email = !empty($selected_columns['email']) ? $row_data[$selected_columns['email']] : '';
        $phone = !empty($selected_columns['phone']) ? $row_data[$selected_columns['phone']] : '';
        $age = !empty($selected_columns['age']) ? $row_data[$selected_columns['age']] : '';
        $sex = !empty($selected_columns['sex']) ? $row_data[$selected_columns['sex']] : '';

        $query = "INSERT INTO attendees (event_id, name, email, phone, age, sex) 
                  VALUES ($event_id, '$name', '$email', '$phone', '$age', '$sex')";
        mysqli_query($conn, $query);
    }

    unset($_SESSION['excel_data'], $_SESSION['event_id']);
    $_SESSION['success'] = "Attendees imported successfully.";
    header('Location: import_attendees.php');
    exit();
}

// Fetch events for dropdown (created by this event creator)
$query_events = "SELECT * FROM events WHERE created_by = {$_SESSION['user_id']} ORDER BY created_at DESC";
$result_events = mysqli_query($conn, $query_events);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Attendees</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
        }
        .step-header {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">Import Attendees</h2>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <!-- Step 1: Upload Excel File -->
    <?php if (!isset($_GET['step']) || $_GET['step'] == 1): ?>
        <div class="card">
            <div class="card-body">
                <h4 class="step-header">Step 1: Upload Excel File</h4>
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="event_id">Select Event:</label>
                        <select name="event_id" id="event_id" class="form-control" required>
                            <?php while ($event = mysqli_fetch_assoc($result_events)): ?>
                                <option value="<?php echo $event['id']; ?>"><?php echo $event['event_name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="excel_file">Upload Excel File:</label>
                        <input type="file" name="excel_file" id="excel_file" class="form-control" required>
                    </div>
                    <button type="submit" name="upload" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- Step 2: Map Columns -->
    <?php if (isset($_GET['step']) && $_GET['step'] == 2): ?>
        <div class="card">
            <div class="card-body">
                <h4 class="step-header">Step 2: Map Excel Columns</h4>
                <form method="post" action="import_attendees.php">
                    <div class="form-group">
                        <label for="name_column">Select Column for Name:</label>
                        <select name="columns[name]" class="form-control" required>
                            <option value="">Select Column</option>
                            <?php foreach (range('A', $highestColumn) as $column): ?>
                                <option value="<?php echo $column; ?>"><?php echo $column; ?> - <?php echo $_SESSION['excel_data'][0][$column]; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="email_column">Select Column for Email:</label>
                        <select name="columns[email]" class="form-control">
                            <option value="">Select Column</option>
                            <?php foreach (range('A', $highestColumn) as $column): ?>
                                <option value="<?php echo $column; ?>"><?php echo $column; ?> - <?php echo $_SESSION['excel_data'][0][$column]; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="phone_column">Select Column for Phone:</label>
                        <select name="columns[phone]" class="form-control">
                            <option value="">Select Column</option>
                            <?php foreach (range('A', $highestColumn) as $column): ?>
                                <option value="<?php echo $column; ?>"><?php echo $column; ?> - <?php echo $_SESSION['excel_data'][0][$column]; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="age_column">Select Column for Age:</label>
                        <select name="columns[age]" class="form-control">
                            <option value="">Select Column</option>
                            <?php foreach (range('A', $highestColumn) as $column): ?>
                                <option value="<?php echo $column; ?>"><?php echo $column; ?> - <?php echo $_SESSION['excel_data'][0][$column]; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sex_column">Select Column for Sex:</label>
                        <select name="columns[sex]" class="form-control">
                            <option value="">Select Column</option>
                            <?php foreach (range('A', $highestColumn) as $column): ?>
                                <option value="<?php echo $column; ?>"><?php echo $column; ?> - <?php echo $_SESSION['excel_data'][0][$column]; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" name="save_mapping" class="btn btn-success">Save Mapping</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

</div>

</body>
</html>