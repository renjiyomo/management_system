<?php
include 'server/connection.php';

$studentId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($studentId) {
    $sql = "SELECT * FROM students WHERE students_id = $studentId";
    $result = $conn->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        echo "<h2>Student Profile</h2>";
        echo "<p><strong>Name:</strong> " . htmlspecialchars($row['student_name']) . "</p>";
        echo "<p><strong>Course:</strong> " . htmlspecialchars($row['course']) . "</p>";
        echo "<p><strong>Year:</strong> " . htmlspecialchars($row['year']) . "</p>";
        echo "<p><strong>Block:</strong> " . htmlspecialchars($row['block']) . "</p>";
    } else {
        echo "<p>Student not found.</p>";
    }
} else {
    echo "<p>Invalid student ID.</p>";
}
?>
