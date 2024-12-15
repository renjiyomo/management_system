<?php
session_start();
require_once 'server/connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$fullName = $_POST['fullName'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];
$profileImage = $_FILES['image'];

// Handle profile image upload
$targetFile = null;
if ($profileImage && $profileImage['name']) {
    $targetDir = "image/";
    $fileName = uniqid() . '_' . basename($profileImage['name']);
    $targetFile = $targetDir . $fileName;

    if (!move_uploaded_file($profileImage['tmp_name'], $targetFile)) {
        echo "Error uploading the image.";
        exit;
    }

    $targetFile = $fileName; // Save only the file name to the database
}

// Build SQL query dynamically
$query = "UPDATE users SET full_name = ?, email = ?, username = ?";
$types = "sss";
$params = [$fullName, $email, $username];

if ($password) {
    $query .= ", password = ?";
    $types .= "s";
    $params[] = $password;
}

if ($targetFile) {
    $query .= ", image = ?";
    $types .= "s";
    $params[] = $targetFile;
}

$query .= " WHERE user_id = ?";
$types .= "i";
$params[] = $user_id;

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    header("Location: manage_account.php?success=1");
} else {
    echo "Error updating account: " . $stmt->error;
}
?>
