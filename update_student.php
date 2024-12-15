<?php
include 'server/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['student_id'])) {
        $student_id = $_POST['student_id'];
        $student_name = $_POST['student_name'];
        $contact_number = $_POST['contact_number'];
        $gender = $_POST['gender'];
        $address = $_POST['address'];
        $birth_date = $_POST['birth_date'];
        $course = $_POST['course'];
        $year = $_POST['year'];
        $block = $_POST['block'];
        $status = $_POST['status'];

        // Prepare update query
        $query = "UPDATE students SET
                    student_name = '$student_name',
                    contact_number = '$contact_number',
                    gender = '$gender',
                    address = '$address',
                    birth_date = '$birth_date',
                    course = '$course',
                    year = '$year',
                    block = '$block',
                    status = '$status'
                  WHERE students_id = '$student_id'";

        if ($conn->query($query) === TRUE) {
            header("Location: admin_page.php");
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>
