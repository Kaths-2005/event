<?php
session_start();
include('db.php'); // Ensure this file connects to your database

// Check if the user is logged in
if (!isset($_SESSION['pid'])) {
    header('Location: login.php');
    exit;
}

$pid = $_SESSION['pid'];

// Fetch user's booked tickets from the database
$query = "SELECT * FROM tickets WHERE pid = '$pid' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Portal - Eventify</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
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
    <h2>Book Event Tickets</h2>
    
    <!-- Event Information -->
    <div class="event-details">
        <h3>DJ Garba Night 2024</h3>
        <p>Date: 19th October 2024</p>
        <p>Time: 6:00 PM - 9:00 PM</p>
        <p>Location: SFIT Campus</p>
        <p>Price: Rs. 250/- per person</p>
    </div>

    <!-- Booking Form -->
    <div class="booking-form">
        <form id="paymentForm">
            <label for="quantity">Number of Tickets:</label>
            <input type="number" id="quantity" name="quantity" min="1" value="1" required>
            
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $_SESSION['name']; ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo isset($_SESSION['email'])?$_SESSION['email']:''; ?>" required>
            
            <label for="pid">PID No.:</label>
            <input type="text" id="pid" name="pid" value="<?php echo $_SESSION['pid']; ?>" readonly>
            
            <button type="button" id="payBtn">Pay Now</button>
        </form>
    </div>

    <!-- Booked Tickets Section -->
    <div class="booked-tickets">
        <h2>Your Booked Tickets</h2>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <tr>
                    
                    <th>Ticket ID</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                    <th>Booking Date</th>
                </tr>
                <?php while ($ticket = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        
                        <td><?php echo htmlspecialchars($ticket['ticket_id']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['quantity']); ?></td>
                        <td>Rs. <?php echo htmlspecialchars($ticket['amount']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['created_at']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>You have not booked any tickets yet.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Razorpay Integration -->
<script>
    document.getElementById('payBtn').onclick = function(e){
        e.preventDefault();

        var quantity = document.getElementById('quantity').value;
        var totalAmount = 250 * quantity; // Assuming Rs. 250 per ticket

        var options = {
            "key": "rzp_test_e1sCL0wRhDJmpj", // Enter the Key ID generated from Razorpay Dashboard
            "amount": totalAmount * 100, // Amount in paisa (1 INR = 100 paisa)
            "currency": "INR",
            "name": "Eventify - DJ Garba Night",
            "description": "Ticket Booking",
            "image": "event_logo.png", // Optional
            "handler": function (response){
                // Handle payment success here
                alert("Payment Successful. Payment ID: " + response.razorpay_payment_id);

                // Prepare data for AJAX request
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "save_booking.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        // Optionally refresh the page or update the booked tickets section
                        location.reload(); // Reloads the page to show the updated booking details
                    }
                };
                // Send data (make sure to URL encode the values)
                xhr.send("pid=" + encodeURIComponent(document.getElementById('pid').value) + 
                         "&quantity=" + encodeURIComponent(quantity) + 
                         "&amount=" + encodeURIComponent(totalAmount) + 
                         "&payment_id=" + encodeURIComponent(response.razorpay_payment_id));
            },
            "prefill": {
                "name": document.getElementById('name').value,
                "email": document.getElementById('email').value,
                "contact": "ENTER_PHONE_NUMBER", // Optional
            },
            "theme": {
                "color": "#F37254"
            }
        };

        var rzp1 = new Razorpay(options);
        rzp1.open();
    }
</script>


</body>
</html>