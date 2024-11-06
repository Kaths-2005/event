<?php
session_start();
include 'database_connection.php'; // File with database connection

if (isset($_POST['reaction_type']) && isset($_POST['event_id'])) {
    $reactionType = $_POST['reaction_type'];
    $eventId = (int)$_POST['event_id'];

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
        // Insert new reaction if not exists
        $newCount = 1;
        $insertQuery = $db->prepare("INSERT INTO reactions (event_id, reaction_type, count) VALUES (?, ?, ?)");
        $insertQuery->execute([$eventId, $reactionType, $newCount]);
    }

    echo json_encode(['count' => $newCount]);
}
?>