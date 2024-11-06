<?php
session_start();
include 'database1.php'; // Ensure this file has the correct database connection

// Ensure admin is logged in
if (!isset($_SESSION['admin_name']) || !isset($_SESSION['admin_email'])) {
    header("Location: log.php");
    exit();
}

// Handling the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $event_title = $_POST['event-title'];
    $event_date = $_POST['event-date'];
    $event_time = $_POST['event-time'];
    $event_price = $_POST['event-price'];
    
    // Handle file upload (event image)
    $event_image = $_FILES['event-image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($event_image);
    move_uploaded_file($_FILES['event-image']['tmp_name'], $target_file); // Save image to server

    // Insert event into the database
    $query = $db->prepare("INSERT INTO events (event_title, event_date, event_time, event_price, event_image) 
                           VALUES (?, ?, ?, ?, ?)");
    $query->execute([$event_title, $event_date, $event_time, $event_price, $target_file]);

    // Redirect to dashboard after posting the event
    header("Location: dashboard.php");
    exit();
}
?>

<!-- HTML form remains the same as in the previous answer -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Event - Eventify</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="navbar">
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="index.html">Send Notification</a></li>
            <li><a href="index.php">Logout</a></li>
        </ul>
    </nav>

    <div class="post-event">
        <h3>Post a New Event</h3>
        <form action="post_event.php" method="POST" enctype="multipart/form-data">
            <label for="event-title">Event Title:</label>
            <input type="text" name="event-title" required>

            <label for="event-date">Date:</label>
            <input type="date" name="event-date" required>

            <label for="event-time">Time:</label>
            <input type="time" name="event-time" required>

            <label for="event-price">Price:</label>
            <input type="text" name="event-price" required>

            <label for="event-image">Event Image:</label>
            <input type="file" name="event-image" required>

            <button type="submit">Post Event</button>
        </form>
    </div>
</body>
</html>
