<?php
session_start();
include('db.php'); // Ensure this file connects to your database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pid = $_POST['pid'];
    $quantity = $_POST['quantity'];
    $amount = $_POST['amount'];
    $payment_id = $_POST['payment_id'];

    // Insert booking into the database
    $query = "INSERT INTO tickets (pid, quantity, amount, payment_id, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiis", $pid, $quantity, $amount, $payment_id);

    if ($stmt->execute()) {
        echo "Booking saved successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>