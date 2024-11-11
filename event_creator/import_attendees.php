<?php
session_start();
include '../includes/auth.php';
include '../includes/db_connect.php';
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_POST['upload'])) {
    $event_id = intval($_POST['event_id']);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
        $file = $_FILES['excel_file']['tmp_name'];
        
        // Load the uploaded Excel file
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

        // Load headers from the first row
        $headers = [];
        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $headers[] = $sheet->getCellByColumnAndRow($col, 1)->getValue(); // Get header values
        }

        // Load data rows, starting from the second row
        $excel_data = [];
        for ($row = 2; $row <= $highestRow; $row++) {
            $row_data = [];
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $cellValue = $sheet->getCellByColumnAndRow($col, $row)->getValue();
                $row_data[] = $cellValue;
            }
            $excel_data[] = $row_data;
        }
        
        // Store headers and data in session
        $_SESSION['excel_headers'] = $headers;
        $_SESSION['excel_data'] = $excel_data;
        $_SESSION['event_id'] = $event_id;

        header('Location: import_attendees.php?step=2');
        exit();
    } else {
        $_SESSION['error'] = "Failed to upload the file.";
    }
}

// Step 2: Column Mapping
if (isset($_POST['save_mapping'])) {
    $selected_columns = $_POST['columns'];
    $excel_data = $_SESSION['excel_data'];
    $event_id = $_SESSION['event_id'];

    foreach ($excel_data as $rowIndex => $row_data) {
        if ($rowIndex == 0) continue;  // Skip header row

        $name = !empty($selected_columns['name']) ? $row_data[$selected_columns['name']] : '';
        $email = !empty($selected_columns['email']) ? $row_data[$selected_columns['email']] : '';
        $phone = !empty($selected_columns['phone']) ? $row_data[$selected_columns['phone']] : '';
        $age = !empty($selected_columns['age']) ? $row_data[$selected_columns['age']] : '';
        $sex = !empty($selected_columns['sex']) ? $row_data[$selected_columns['sex']] : '';

        $query = "INSERT INTO attendees (event_id, name, email, phone, age, sex) VALUES ($event_id, '$name', '$email', '$phone', '$age', '$sex')";
        mysqli_query($conn, $query);
    }

    unset($_SESSION['excel_data'], $_SESSION['event_id'], $_SESSION['excel_headers']);
    $_SESSION['success'] = "Attendees imported successfully.";
    header('Location: import_attendees.php');
    exit();
}

// Step 1: Fetch events for dropdown
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
                    <?php
                    // Store the headers from session for dropdowns
                    $headers = $_SESSION['excel_headers'];
                    ?>

                    <div class="form-group">
                        <label for="name_column">Select Column for Name:</label>
                        <select name="columns[name]" class="form-control" required>
                            <option value="">Select Column</option>
                            <?php foreach ($headers as $index => $header): ?>
                                <option value="<?php echo $index; ?>"><?php echo $header; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="email_column">Select Column for Email:</label>
                        <select name="columns[email]" class="form-control">
                            <option value="">Select Column</option>
                            <?php foreach ($headers as $index => $header): ?>
                                <option value="<?php echo $index; ?>"><?php echo $header; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="phone_column">Select Column for Phone:</label>
                        <select name="columns[phone]" class="form-control">
                            <option value="">Select Column</option>
                            <?php foreach ($headers as $index => $header): ?>
                                <option value="<?php echo $index; ?>"><?php echo $header; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="age_column">Select Column for Age:</label>
                        <select name="columns[age]" class="form-control">
                            <option value="">Select Column</option>
                            <?php foreach ($headers as $index => $header): ?>
                                <option value="<?php echo $index; ?>"><?php echo $header; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sex_column">Select Column for Sex:</label>
                        <select name="columns[sex]" class="form-control">
                            <option value="">Select Column</option>
                            <?php foreach ($headers as $index => $header): ?>
                                <option value="<?php echo $index; ?>"><?php echo $header; ?></option>
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
