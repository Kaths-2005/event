<?php
// Start the session
session_start();

// Initialize error message
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_POST['email'];
    $pid = $_POST['pid'];

    // Basic validation
    if (empty($username) || empty($password) || empty($confirm_password) || empty($email) || empty($pid)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Database connection
        $conn = new mysqli('localhost', 'root', '', 'eventify'); // Replace with your database credentials
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Insert admin data into the database
        $stmt = $conn->prepare("INSERT INTO admin (username, password, email, pid) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $hashed_password, $email, $pid);

        if ($stmt->execute()) {
            // Redirect to the admin dashboard after successful registration
            header("Location: addash.php");
            exit();
        } else {
            $error = "Error: " . $stmt->error;
        }

        // Close the connection
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('sfit.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
        }

        form {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            width: 300px;
        }

        h2 {
            color: white; /* Change heading color to white */
        }

        input[type="text"], input[type="password"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: navy;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #1a237e;
        }

        .error {
            color: red;
            margin-top: 10px;
        }

        .login-link {
            margin-top: 10px;
            display: block;
            color: white;
            text-decoration: none;
        }

        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <form method="post" action="">
        <h2>Admin Registration</h2>
        <?php if (!empty($error)) { echo '<div class="error">'.$error.'</div>'; } ?>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <input type="email" name="email" placeholder="Email ID" required>
        <input type="text" name="pid" placeholder="PID" required>
        <input type="submit" value="Register">
        <a href="log.php" class="login-link">Already have an account? Login here</a>
    </form>
</body>
</html>
