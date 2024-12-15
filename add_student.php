<?php
session_start();
include 'server/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_name = $_POST['student_name'];
    $contact_number = $_POST['contact_number'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $birth_date = $_POST['birth_date'];
    $course = $_POST['course'];
    $year = $_POST['year'];
    $block = $_POST['block'];
    $status = $_POST['status'];

    $query = "INSERT INTO students (student_name, contact_number, gender, address, birth_date, course, year, block, status) 
              VALUES ('$student_name', '$contact_number', '$gender', '$address', '$birth_date', '$course', '$year', '$block', '$status')";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['success_message'] = "Student successfully added!";
        header("Location: admin_page.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error: " . mysqli_error($conn);
        header("Location: admin_page.php");
        exit();
    }
}
?>
