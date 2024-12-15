<?php
include 'server/connection.php';

header('Content-Type: application/json');

if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
    $student_id = (int)$_GET['id'];

    if ($stmt = $conn->prepare("SELECT * FROM students WHERE students_id = ?")) {
        $stmt->bind_param('i', $student_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Translate status to a word
            $status_mapping = [
                'i' => 'Irregular',
                'r' => 'Regular',
                'g' => 'Graduated', // Add more as needed
            ];
            $status_word = isset($status_mapping[$row['status']]) ? $status_mapping[$row['status']] : 'Unknown';

            echo json_encode([
                'student_name' => $row['student_name'],
                'course' => $row['course'],
                'year' => $row['year'],
                'block' => $row['block'],
                'contact_number' => $row['contact_number'],
                'address' => $row['address'],
                'birth_date' => $row['birth_date'],
                'gender' => $row['gender'],
                'status' => $status_word, // Return the status as a word
            ]);
        } else {
            echo json_encode(['error' => 'Student not found.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['error' => 'Failed to prepare statement.']);
    }
} else {
    echo json_encode(['error' => 'Invalid or missing student ID.']);
}

$conn->close();
?>
