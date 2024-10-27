<?php
session_start();
$user_role = $_SESSION['role'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="./index.php">Attendance System</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="./index.php">Home</a>
      </li>

      <!-- Admin Links -->
      <?php if ($user_role == 'admin'): ?>
      <li class="nav-item">
        <a class="nav-link" href="/admin/dashboard.php">Dashboard</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/admin/manage_users.php">Manage Users</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/admin/manage_events.php">Manage Events</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/admin/reports.php">Reports</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/admin/settings.php">Settings</a>
      </li>

      <!-- Event Creator Links -->
      <?php elseif ($user_role == 'event_creator'): ?>
      <li class="nav-item">
        <a class="nav-link" href="../Attendance/event_creator/dashboard.php">Dashboard</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../Attendance/event_creator/create_event.php">Create Event</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../Attendance/event_creator/manage_events.php">Manage Events</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../Attendance/event_creator/import_attendees.php">Import Attendees</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../Attendance/event_creator/assign_checkers.php">Assign Checkers</a>
      </li>

      <!-- Checker Links -->
      <?php elseif ($user_role == 'checker'): ?>
      <li class="nav-item">
        <a class="nav-link" href="../Attendance/checker/dashboard.php">Dashboard</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../Attendance/checker/event_list.php">Assigned Events</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../Attendance/checker/check_in.php">Check In Attendees</a>
      </li>

      <?php endif; ?>

      <!-- Logout -->
      <li class="nav-item">
        <a class="nav-link" href="/logout.php">Logout</a>
      </li>
    </ul>
  </div>
</nav>

