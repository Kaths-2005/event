<?php
session_start();
include 'database.php'; // Includes the database connection file
include 'database1.php';
$events = [
    ["id" => 1, "image" => "garba.jpg", "title" => "The Much-Awaited DJ x Garba Night 2024", "date" => "19th October 2024", "time" => "6:00 PM - 9:00 PM", "price" => "Rs. 250/- Per Person"],
    ["id" => 2, "image" => "git-smart.jpg", "title" => "Git Smart Workshop", "date" => "23rd October 2024", "time" => "2:00 PM - 3:00 PM", "price" => "Free"],
];

$reaction_counts = [];
foreach ($events as $event) {
    // Fetch initial reaction counts for each event
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
    <title>Dashboard - Eventify</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="calendar.php">Personal Calendar</a></li>
            <li><a href="payment.php">Payment Portal</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="left-panel">
            <h2>Your Calendar</h2>
            <iframe src="https://calendar.google.com/calendar/embed?src=your_public_calendar_id%40group.calendar.google.com&ctz=Asia/Kolkata" style="border: 0" width="300" height="300" frameborder="0" scrolling="no"></iframe>
        <!-- Alert Section -->
        <div class="alert-section">
                <h3 id=alertstext>Alerts</h3>
                <ul class="alertul">
                    <li>🚨 Don't forget to register for the DJ x Garba Night!</li>
                    <button class=alertbutton><a class=alerttext href="payment.php">Book Now</a></button>
                    <li>📅 Check your personal calendar for upcoming events.</li>
                    <li>⚠️ Limited seats available for the Git Smart Workshop!</li>
                    <button class=alertbutton><a href="" class=alerttext>Register for Free!</a></button>
                </ul>
            </div>

            </div>
        
        <div class="center-panel">
            <div class="user-info">
                <div class="info-box">
                    <p>Name: <?php echo $_SESSION['name']; ?></p>
                    <p>PID No.: <?php echo $_SESSION['pid']; ?></p>
                </div>
            </div>
            
            <div class="events">
                <?php foreach ($events as $event): ?>
                    <div class="event-post">
                        <img src="<?php echo $event['image']; ?>" alt="<?php echo $event['title']; ?>">
                        <p><?php echo $event['title']; ?></p>
                        <p>🗓: <?php echo $event['date']; ?> 🕕: <?php echo $event['time']; ?></p>
                        <p>💸: <?php echo $event['price']; ?></p>
                        <div class="reactions">
                            <span class="reaction-button" data-reaction="heart" data-event="<?php echo $event['id']; ?>">❤️ <span id="heart-count-<?php echo $event['id']; ?>"><?php echo $reaction_counts[$event['id']]['heart'] ?? 0; ?></span></span>
                            <span class="reaction-button" data-reaction="thumbsup" data-event="<?php echo $event['id']; ?>">👍 <span id="thumbsup-count-<?php echo $event['id']; ?>"><?php echo $reaction_counts[$event['id']]['thumbsup'] ?? 0; ?></span></span>
                            <span class="reaction-button" data-reaction="thumbsdown" data-event="<?php echo $event['id']; ?>">👎 <span id="thumbsdown-count-<?php echo $event['id']; ?>"><?php echo $reaction_counts[$event['id']]['thumbsdown'] ?? 0; ?></span></span>
                            <span class="reaction-button" data-reaction="party" data-event="<?php echo $event['id']; ?>">🎉 <span id="party-count-<?php echo $event['id']; ?>"><?php echo $reaction_counts[$event['id']]['party'] ?? 0; ?></span></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $('.reaction-button').on('click', function() {
            let reactionType = $(this).data('reaction');
            let eventId = $(this).data('event');
            
            $.ajax({
                url: '', // Same page URL
                type: 'POST',
                data: { reaction_type: reactionType, event_id: eventId },
                success: function(response) {
                    console.log("Response received:", response); // Log the response
                    let result = JSON.parse(response);
                    let newCount = result.count;
                    $('#' + reactionType + '-count-' + eventId).text(newCount); // Use eventId here
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        });
    });
    </script>

    <?php
    // PHP Script to Handle AJAX Request for Reactions
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reaction_type']) && isset($_POST['event_id'])) {
        $reactionType = $_POST['reaction_type'];
        $eventId = (int)$_POST['event_id'];

        try {
            // Check if reaction exists for this event and type
            $query = $db->prepare("SELECT * FROM reactions WHERE event_id = ? AND reaction_type = ?");
            $query->execute([$eventId, $reactionType]);
            $reaction = $query->fetch();

            if ($reaction) {
                // Update count if reaction already exists
                $newCount = $reaction['count'] + 1;
                $updateQuery = $db->prepare("UPDATE reactions SET count = ? WHERE event_id = ? AND reaction_type = ?");
                $updateQuery->execute([$newCount, $eventId, $reactionType]);
            } else {
                // Insert new reaction if it does not exist
                $newCount = 1;
                $insertQuery = $db->prepare("INSERT INTO reactions (event_id, reaction_type, count) VALUES (?, ?, ?)");
                $insertQuery->execute([$eventId, $reactionType, $newCount]);
            }

            // Return the new count
            echo json_encode(['count' => $newCount]);
        } catch (PDOException $e) {
            // Log error message
            error_log("Database error: " . $e->getMessage());
            echo json_encode(['error' => 'Failed to update reactions']);
        }
        exit;
    }
    ?>
</body>
</html>