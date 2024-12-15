<?php
// Database connection variables
$host = "localhost";      // Hostname (usually localhost)
$username = "root";       // Database username (default for XAMPP/WAMP is "root")
$password = "";           // Database password (leave empty for local setups)
$database = "management_system"; // Name of your database

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
