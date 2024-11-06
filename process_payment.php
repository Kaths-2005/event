<?php
session_start();
include('db.php'); // Ensure you connect to your database

// Get data from AJAX request
$data = json_decode(file_get_contents("php://input"));

$payment_id = $data->payment_id;
$quantity = $data->quantity;
$name = $data->name;
$email = $data->email;
$pid = $data->pid;
$totalAmount = 250 * $quantity; // Calculate the total amount

// Here you should add Razorpay payment verification code (using Razorpay API to verify payment)

$ticket_id = uniqid("TICKET_"); // Unique ticket ID

// Store ticket information in the database
$sql = "INSERT INTO tickets (ticket_id, payment_id, pid, name, email, quantity, amount) VALUES ('$ticket_id', '$payment_id', '$pid', '$name', '$email', '$quantity', '$totalAmount')";
$result = mysqli_query($conn, $sql);

if ($result) {
    echo json_encode([
        'success' => true,
        'ticket_id' => $ticket_id,
        'quantity' => $quantity,
        'amount' => $totalAmount
    ]);
} else {
    echo json_encode(['success' => false]);
}
?>