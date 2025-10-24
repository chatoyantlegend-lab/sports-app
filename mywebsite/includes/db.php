<?php
// Database connection settings
$host = "localhost";
$user = "root";      // your MySQL username (default in XAMPP)
$pass = "";          // your MySQL password (empty by default)
$dbname = "myapp"; // name of your database

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else 
   


// Set charset (important for emojis, special characters, etc.)
$conn->set_charset("utf8mb4");
?>
