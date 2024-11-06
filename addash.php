<?php
session_start(); // Start session here (at the top of the page)
include 'database1.php'; // Include your database connection

// Ensure session variables are set
if (!isset($_SESSION['admin_name']) || !isset($_SESSION['admin_email'])) {
    // If session variables are not set, redirect to login page
    header("Location: log.php");
    exit();
}

// Get admin data from session
$admin_name = $_SESSION['admin_name'];
$admin_email = $_SESSION['admin_email'];

// Fetch events from database (replace this array with your DB logic)
$events = [
    ["id" => 1, "image" => "garba.jpg", "title" => "The Much-Awaited DJ x Garba Night 2024", "date" => "19th October 2024", "time" => "6:00 PM - 9:00 PM", "price" => "Rs. 250/- Per Person"],
    ["id" => 2, "image" => "git-smart.jpg", "title" => "Git Smart Workshop", "date" => "23rd October 2024", "time" => "2:00 PM - 3:00 PM", "price" => "Free"],
];

// Reaction counts
$reaction_counts = [];
foreach ($events as $event) {
    $query = $db->prepare("SELECT * FROM reactions WHERE event_id = ?");
    $query->execute([$event['id']]);
    $reactions = $query->fetchAll(PDO::FETCH_ASSOC);

    foreach ($reactions as $reaction) {
        $reaction_counts[$event['id']][$reaction['reaction_type']] = $reaction['count'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Eventify</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-image: url('sfit.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: black;
            text-align: center;
        }

        .navbar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            background-color: rgba(255, 255, 255, 0.8);
            width: 100%;
            text-align: center;
            padding: 10px;
        }

        .navbar ul li {
            display: inline;
            margin-right: 20px;
        }

        .navbar ul li a {
            color: black;
            text-decoration: none;
        }

        /* Container for Admin details and Events section */
        .content-container {
            width: 80%;
            display: flex;
            flex-direction: row;
            margin-top: 20px;
            justify-content: space-between;
        }

        /* User Details Box */
        .welcome-box {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            width: 45%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Post Event Form */
        .post-event {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            width: 45%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .post-event input[type="text"], 
        .post-event input[type="date"], 
        .post-event input[type="time"], 
        .post-event input[type="file"], 
        .post-event button {
            margin-bottom: 10px;
            width: 100%;
        }

        /* Events Section */
        .events-section {
            width: 45%;
            margin-top: 20px;
        }

        .events-section .event-post {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .events-section .event-post img {
            width: 100px;
            height: auto;
            margin-bottom: 10px;
        }

        .events-section .event-post p {
            color: black;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="index.html">Send Notification</a></li>
            <li><a href="index.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Welcome Admin -->
    <div class="welcome-box">
        <h2>Welcome, Admin</h2>
        <p>Name: <?php echo htmlspecialchars($admin_name); ?></p>
        <p>Email: <?php echo htmlspecialchars($admin_email); ?></p>
    </div>

    <!-- Content Section (Events & Post Event Form) -->
    <div class="content-container">
        <!-- Post Event Form -->
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

        <!-- Events Section -->
        <div class="events-section">
            <?php foreach ($events as $event): ?>
                <div class="event-post">
                    <img src="<?php echo htmlspecialchars($event['image']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>">
                    <p><?php echo htmlspecialchars($event['title']); ?></p>
                    <p>🗓: <?php echo htmlspecialchars($event['date']); ?> 🕕: <?php echo htmlspecialchars($event['time']); ?></p>
                    <p>💸: <?php echo htmlspecialchars($event['price']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
