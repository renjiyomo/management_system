<?php
include 'server/connection.php';

if (isset($_GET['id'])) {
    $student_id = $_GET['id'];
    $query = "SELECT * FROM students WHERE students_id = '$student_id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        echo json_encode([
            'id' => $student['students_id'],
            'name' => $student['student_name'],
            'contact_number' => $student['contact_number'],
            'gender' => $student['gender'],
            'address' => $student['address'],
            'birth_date' => $student['birth_date'],
            'course' => $student['course'],
            'year' => $student['year'],
            'block' => $student['block'],
            'status' => $student['status']
        ]);
    } else {
        echo json_encode(['error' => 'Student not found']);
    }
} else {
    echo json_encode(['error' => 'No student ID provided']);
}
?>
