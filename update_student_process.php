<?php
include 'server/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['students_id'];
    $student_name = $_POST['student_name'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $birth_date = $_POST['birth_date'];
    $course = $_POST['course'];
    $block = $_POST['block'];

    // Update query
    $sql = "UPDATE students 
            SET student_name = ?, gender = ?, address = ?, birth_date = ?, course = ?, block = ? 
            WHERE students_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $student_name, $gender, $address, $birth_date, $course, $block, $student_id);

    if ($stmt->execute()) {
        header("Location: admin_page.php");
        exit;
    } else {
        echo "Error updating student: " . $conn->error;
    }
} else {
    echo "Invalid request!";
}
?>
