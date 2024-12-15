<?php
include 'server/connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $studentName = $_POST['student_name'];
    $gender = $_POST['gender'];
    $birthDate = $_POST['birth_date'];
    $address = $_POST['address'];
    $course = $_POST['course'];
    $block = $_POST['block'];
    $status = $_POST['status'];
    $dateOfEntry = date('Y-m-d H:i:s'); // Get the current timestamp

    // Prepare the SQL query to insert the new student
    $sql = "INSERT INTO students (student_name, gender, birth_date, address, course, block, status, date_of_entry) 
            VALUES ('$studentName', '$gender', '$birthDate', '$address', '$course', '$block', '$status', '$dateOfEntry')";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        // Redirect to the student list page after successful insertion
        header("Location: admin_page.php"); 
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;a
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student Modal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Header -->
<header class="text-center my-4">
    <h1>Add New Student</h1>
</header>





<!-- Footer -->
<footer class="text-center py-4">
    &copy; 2024 Student Management System. All rights reserved.
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const searchBtn = document.getElementById("searchBtn");
        const searchInput = document.getElementById("searchInput");

        searchBtn.addEventListener("click", () => {
            const searchTerm = searchInput.value;
            window.location.href = `?search=${searchTerm}`; // Reload the page with the search query
        });

        // Optional: Add event listener for Enter key to trigger search
        searchInput.addEventListener("keydown", (e) => {
            if (e.key === "Enter") {
                searchBtn.click();
            }
        });
    });
    </script>

</body>
</html>

<?php
// Close connection
$conn->close();
?>
