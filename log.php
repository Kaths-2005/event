<?php
// Start the session at the very top of the file (no whitespace above this line)
session_start();

include 'database1.php'; // Include the database connection file

$error = '';


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'eventify'); // Replace with your database credentials
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Debugging: Check if form data is being passed
    // echo '<pre>';
    // var_dump($_POST);  // Debugging the form data
    // echo '</pre>';

    // Prepare the statement to fetch user info
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the username exists
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        // Check if password matches
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true; // Set session variable for admin
            $_SESSION['admin_name'] = $admin['username']; // Store username in session
            $_SESSION['admin_email'] = $admin['email']; // Store email in session

            // Debugging: Check session after successful login
            // echo '<pre>';
            // var_dump($_SESSION);  // Debugging the session data
            // echo '</pre>';

            // Ensure no output before the redirect
            header("Location: addash.php"); // Redirect to admin dashboard
            exit();  // Make sure no further code is executed
        } else {
            $error = "Invalid username or password";
        }
    } else {
        $error = "Invalid username or password";
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
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

        h2 {
            color: white;
        }
        
        form {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            width: 300px;
        }

        input[type="text"], input[type="password"] {
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

        .register-link {
            margin-top: 10px;
            display: block;
            color: white;
            text-decoration: none;
        }

        .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <form method="post" action="">
        <h2>Admin Login</h2>
        <?php if (isset($error)) { echo '<div class="error">'.$error.'</div>'; } ?>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" value="Login">
        <a href="reg.php" class="register-link">Not logged in? Register here</a>
    </form>
</body>
</html>
