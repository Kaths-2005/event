<?php
session_start();
if (isset($_SESSION['name'])) {
    echo "<h1>Thank you for booking with Eventify, " . $_SESSION['name'] . "!</h1>";
    echo "<p>Your payment was successful. We look forward to seeing you at the event.</p>";
} else {
    echo "Please log in to complete your payment.";
}
?>