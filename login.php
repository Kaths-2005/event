<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'database.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pid = $_POST['pid'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL statement to fetch the user with the provided PID and email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE pid = ? AND email = ?");
    $stmt->execute([$pid, $email]);
    $user = $stmt->fetch();

    // Check if user exists and verify password
    if ($user && password_verify($password, $user['password'])) {
        // Start a session and store user data if needed
        session_start();
        $_SESSION['user_id'] = $user['id'];   // Store user ID
        $_SESSION['pid'] = $user['pid'];      // Store user PID
        $_SESSION['name'] = $user['name'];    // Store user Name
        
        // Redirect to dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        // Display error message if login fails
        $error_message = "Invalid PID, email, or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Login</title>
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
            padding: 40px; /* Adjusted padding for more space */
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            width: 300px; /* Set a fixed width for the box */
            box-sizing: border-box; /* Include padding in width */
        }

        h2 {
            color: black; /* Header text color */
            text-align: center; /* Center the heading */
            margin: 0 0 20px; /* Remove margin on top and add space below */
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

        /* Register link style */
        .register-link {
            text-align: center; /* Center the link */
            margin-top: 15px; /* Space above the link */
        }
    </style>
</head>
<body>
    <form action="" method="POST">
        <h2>Login</h2> <!-- Header inside the form -->
        
        <?php if (isset($error_message)) : ?>
            <p class="error"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>

        <label for="pid">PID (6 digits):</label>
        <input type="text" name="pid" required pattern="\d{6}" title="Enter a 6-digit PID">

        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <input id="login_submit" type="submit" value="Login">

        <div class="register-link">
            <p>If not signed in, <a href="register.php">Register Here</a></p>
        </div>
    </form>
</body>
</html>
