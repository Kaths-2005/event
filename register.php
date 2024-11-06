<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'database.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];  // Get name from form
    $pid = $_POST['pid'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password

    // Prepare SQL statement to insert user data
    $stmt = $pdo->prepare("INSERT INTO users (name, pid, email, password) VALUES (?, ?, ?, ?)");

    try {
        // Execute statement
        $stmt->execute([$name, $pid, $email, $password]);
        // Registration successful, redirect to dashboard
        header("Location: dashboard.php");
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // 23000 is the code for unique constraint violation
            echo "Registration failed! The email or PID is already in use.";
        } else {
            echo "An error occurred: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Register</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background-image: url('sfit.jpg'); /* Background image */
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            color: black; /* Text color */
        }

        form {
            background: rgba(255, 255, 255, 0.8); /* Semi-transparent background */
            padding: 20px; /* Adjusted padding for a smaller box */
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            width: 300px; /* Set a fixed width for the box */
            box-sizing: border-box; /* Include padding in width */
        }

        h2 {
            color: black; /* Header text color */
            text-align: center; /* Center the heading */
        }

        input[type="text"], 
        input[type="email"], 
        input[type="password"] {
            width: 100%; /* Full width of the form */
            padding: 10px; /* Adjust padding for better appearance */
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px; /* Adjust font size */
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: navy; /* Button color */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%; /* Full width for the button */
        }

        input[type="submit"]:hover {
            background-color: #1a237e; /* Darker navy on hover */
        }

        /* Error message style */
        p.error {
            color: red;
            text-align: center; /* Center error message */
            margin-top: 10px; /* Space above the error message */
        }

        /* Link back to login style */
        p {
            text-align: center;
            margin-top: 15px;
        }

        a {
            color: navy;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <form action="" method="POST">
        <h2>Register</h2>

        <label for="name">Name:</label>
        <input type="text" name="name" required><br>

        <label for="pid">PID (6 digits):</label>
        <input type="text" name="pid" required pattern="\d{6}" title="Enter a 6-digit PID"><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Register">

        <!-- Link back to login page for already registered users -->
        <p>
            Already registered? <a href="login.php">Go back to Login</a>
        </p>
    </form>
</body>
</html>
