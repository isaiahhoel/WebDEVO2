<?php

$host = 'localhost';
$username = 'your_username';//CONNECTION PARAMETERS
$password = 'your_password';
$database = 'your_database';

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    
    //securing password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into the database
    $insertQuery = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    
    if ($stmt) {
        $stmt->bind_param("sss", $username, $email, $hashedPassword);
        if ($stmt->execute()) {
            // Registration successful
            echo "Account created! Please redirect to the login page to log in to your account.";
        } else {
            // Registration failed
            echo "Registration failed. Please try again.";
        }
        $stmt->close();
    } else {
        echo "Error in database query.";
    }
}

// Close the database connection
$conn->close();
?>