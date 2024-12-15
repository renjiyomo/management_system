<?php
session_start();
include 'server/connection.php';

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'a') {
    // Redirect to login page if not logged in or not an admin
    header("Location: login.php");
    exit();
}

// Check if an id is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $student_id = $_GET['id'];

    // Prepare the SQL query to delete the student record
    $sql = "DELETE FROM students WHERE students_id = ?";

    // Prepare the statement to prevent SQL injection
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameter and execute the statement
        $stmt->bind_param("i", $student_id);
        if ($stmt->execute()) {
            // Redirect back to the student list after successful deletion
            header("Location: admin_page.php");  // Change this to the page you want to redirect to
            exit();
        } else {
            echo "Error deleting student: " . $conn->error;
        }
    } else {
        echo "Error preparing query: " . $conn->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Invalid student ID!";
}

// Close the connection
$conn->close();
?>
