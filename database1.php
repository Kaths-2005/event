<?php
// Start a session to store any error messages or information if needed
//session_start();

// Database credentials (you can replace these with your own credentials)
$host = 'localhost';  // usually localhost for local development
$dbname = 'eventify';  // replace with your actual database name
$username = 'root';  // default username for XAMPP/MySQL
$password = '';  // default password for XAMPP/MySQL (empty string)

try {
    // Create a new PDO instance and set error mode to exception
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // You can store a success message in session if needed
    $_SESSION['db_status'] = 'Database connection successful!';
    
} catch (PDOException $e) {
    // Catch any connection errors and store the message in the session
    $_SESSION['db_error'] = 'Database connection failed: ' . $e->getMessage();
    
    // Redirect to an error page or show the error message
    header("Location: error_page.php");
    exit();
}
?>
