<?php
include '../includes/db_connect.php';

if (isset($_POST['query'], $_POST['event_id'])) {
    $search = "%" . $_POST['query'] . "%";
    $event_id = intval($_POST['event_id']);

    $query = "SELECT id, name, email FROM attendees WHERE event_id = ? AND (name LIKE ? OR email LIKE ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $event_id, $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<li class="dropdown-item" data-id="' . $row['id'] . '">' . htmlspecialchars($row['name']) . ' (' . htmlspecialchars($row['email']) . ')</li>';
        }
    } else {
        echo '<li class="dropdown-item">No results found</li>';
    }
}
?>
