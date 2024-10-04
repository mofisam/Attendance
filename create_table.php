<?php
include 'db_connect.php';

// SQL to create the tables

// Attendees table
$sql = "CREATE TABLE IF NOT EXISTS attendees (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    age INT(3),
    sex ENUM('Male', 'Female', 'Other'),
    community_id INT(11),
    classification_id INT(11),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$conn->query($sql);

// Admins table
$sql = "CREATE TABLE IF NOT EXISTS admins (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$conn->query($sql);

// Users table (for event creators and checkers)
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'event_creator', 'checker') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$conn->query($sql);

// Events table
$sql = "CREATE TABLE IF NOT EXISTS events (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(255) NOT NULL,
    event_date DATE NOT NULL,
    created_by INT(11),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
)";
$conn->query($sql);

// Event_Teams table (assign checkers to events)
$sql = "CREATE TABLE IF NOT EXISTS event_teams (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    event_id INT(11) NOT NULL,
    user_id INT(11) NOT NULL,
    role ENUM('checker') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
$conn->query($sql);

// Attendance table
$sql = "CREATE TABLE IF NOT EXISTS attendance (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    event_id INT(11) NOT NULL,
    user_id INT(11) NOT NULL,
    is_present BOOLEAN DEFAULT 0,
    marked_by INT(11),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES attendees(id) ON DELETE CASCADE,
    FOREIGN KEY (marked_by) REFERENCES users(id)
)";
$conn->query($sql);

// Communities table
$sql = "CREATE TABLE IF NOT EXISTS communities (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    community_name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$conn->query($sql);

// Classifications table
$sql = "CREATE TABLE IF NOT EXISTS classifications (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    classification_name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$conn->query($sql);

// Event_Settings table
$sql = "CREATE TABLE IF NOT EXISTS event_settings (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    event_id INT(11),
    field_name VARCHAR(255),
    is_enabled BOOLEAN DEFAULT 0,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
)";
$conn->query($sql);

// Import_Mappings table
$sql = "CREATE TABLE IF NOT EXISTS import_mappings (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    event_id INT(11),
    excel_column VARCHAR(255),
    db_field VARCHAR(255),
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
)";
$conn->query($sql);

$conn->close();
?>
