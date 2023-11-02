<?php

$host = '';
$username = 'your_username';
$password = 'your_password';//CONNECTION PARAMETERS
$database = 'your_database';

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Retrieve the user's hashed password from the database
    $selectQuery = "SELECT username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($selectQuery);

    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($username, $hashedPassword);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashedPassword)) {

                session_start();
            
                // Store user information in the session
                $_SESSION['username'] = $username;
                
                // Redirect to the main page
                echo "<script>window.location = 'Home.html';</script>";
                exit();
            } else {
                echo "Incorrect username or password.";
            }
        }
        $stmt->close();
    } else {
        echo "Error in database query.";
    }
}

// Close the database connection
$conn->close();
?>




