<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
    $file = $_FILES['excel_file']['tmp_name'];
    
    // Load the spreadsheet
    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();
    
    // Get column names (assuming they are in the first row)
    $highestColumn = $sheet->getHighestColumn(); // e.g. 'E'
    $highestRow = 1; // we are only interested in the first row for headers
    
    $columnNames = [];
    
    for ($col = 'A'; $col <= $highestColumn; $col++) {
        $columnNames[] = $sheet->getCell($col . $highestRow)->getValue();
    }

    echo "<h2>Column Names:</h2>";
    echo "<ul>";
    foreach ($columnNames as $columnName) {
        echo "<li>" . htmlspecialchars($columnName) . "</li>";
    }
    echo "</ul>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Excel</title>
</head>
<body>
    <h1>Upload Excel File</h1>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="excel_file" accept=".xlsx, .xls" required>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
