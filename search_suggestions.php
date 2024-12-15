<?php
include 'server/connection.php';

$searchTerm = isset($_GET['term']) ? trim($_GET['term']) : '';
$suggestions = [];

if (!empty($searchTerm)) {
    $stmt = $conn->prepare("SELECT students_id, student_name FROM students WHERE student_name LIKE CONCAT('%', ?, '%') LIMIT 10");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $suggestions[] = [
            'id' => $row['students_id'],
            'name' => $row['student_name']
        ];
    }

    $stmt->close();
}

echo json_encode($suggestions);
?>
