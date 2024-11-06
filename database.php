<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection details
$host = 'localhost';  
$dbname = 'eventify';  // Database name
$username = 'root';  
$password = '';  

try {
    // Connect to MySQL server (without specifying a database yet)
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create the new database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    
    // Now switch to the new database
    $pdo->exec("USE $dbname");

    // Create the users table if it doesn't exist
    $createUsersTable = "
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            pid VARCHAR(6) NOT NULL UNIQUE,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL
        )
    ";
    $pdo->exec($createUsersTable);

    // Comment out the success message to avoid interfering with redirection
    // echo "Connected successfully to the 'eventify' database and 'users' table created or already exists.";  

} catch (PDOException $e) {
    // Stop the script and display the error message if the connection fails
    die("Connection failed: " . $e->getMessage());
}
try {
    $db = new PDO('mysql:host=localhost;dbname=eventify;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}