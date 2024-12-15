<?php
include 'server/connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch student details
    $sql = "SELECT * FROM students WHERE students_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
} else {
    echo "No student ID provided.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel="stylesheet" href="css/viewstudent.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Student Profile</h1>
            <p>Details of the selected student</p>
        </div>
        <div class="profile">
            <div class="section">
                <h2><?php echo $student['student_name']; ?></h2>
                <p><strong>Gender:</strong> <?php echo $student['gender']; ?></p>
                <p><strong>Birth Date:</strong> <?php echo $student['birth_date']; ?></p>
                <p><strong>Address:</strong> <?php echo $student['address']; ?></p>
            </div>
            <div class="section">
                <h2>Class Details</h2>
                <p><strong>Course & Block:</strong> <?php echo $student['course'] . " " . $student['block']; ?></p>
                <p><strong>Date of Entry:</strong> <?php echo $student['date_of_entry']; ?></p>
                <p><strong>Status:</strong> 
                <?php 
                    if ($student['status'] === 'r') {
                        echo 'Regular';
                    } elseif ($student['status'] === 'g') {
                        echo 'Graduate';
                    } else {
                        echo 'Irregular';
                    }
                ?>
</p>

            </div>
        </div>
        <div class="buttons">
            <a href="admin_page.php"><button>Back</button></a>
        </div>
    </div>
</body>
</html>
