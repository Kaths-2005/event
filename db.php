<?php
$host = "localhost";
$username = "root";  // Default XAMPP username
$password = "";  // Leave it blank if no password
$dbname = "eventify";  // Your newly created database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>