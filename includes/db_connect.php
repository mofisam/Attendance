<?php
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = "1234";     // Replace with your database password
$dbname = "attendance_system"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
