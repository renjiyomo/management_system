<?php
session_start();
include 'server/connection.php';

$successMessage = '';
if (isset($_GET['success'])) {
    $successMessage = "Student added successfully!";
} elseif (isset($_GET['error'])) {
    $successMessage = "Error: " . htmlspecialchars($_GET['error']);
}

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'a') {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];

// Fetch admin details (optional)
$query_admin = "SELECT * FROM users WHERE user_id = '$admin_id' AND user_type = 'a'";
$result_admin = mysqli_query($conn, $query_admin);
if (!$result_admin || mysqli_num_rows($result_admin) != 1) {
    echo "Admin not found!";
    exit();
}

// Fetch search term
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$sql = "SELECT students_id, student_name, course, block FROM students";
if ($searchTerm) {
    $sql .= " WHERE student_name LIKE '%" . $conn->real_escape_string($searchTerm) . "%' 
              OR course LIKE '%" . $conn->real_escape_string($searchTerm) . "%' 
              OR block LIKE '%" . $conn->real_escape_string($searchTerm) . "%'";
}

$result = $conn->query($sql);
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link rel="stylesheet" href="css/userpage.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>


<nav class="navbar navbar-expand-lg bg-white shadow-sm">
        <div class="container-fluid">
            <span class="navbar-brand text-warning fw-bold">Welcome, Admin!</span>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <form class="d-flex mx-auto" role="search" method="GET">
                    <input class="form-control me-2 rounded-pill border-warning" type="search" id="searchInput" name="search" placeholder="Search..." aria-label="Search" value="<?= htmlspecialchars($searchTerm); ?>">
                    <button class="btn btn-warning rounded-pill" id="searchBtn" type="submit">Search</button>
                </form>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-warning fw-bold" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
   <!-- Add Student Button -->
<div class="text-center mb-4" style="padding-left: 50%;" >
    <button type="button" class="btn btn-warning rounded-pill" data-bs-toggle="modal" data-bs-target="#addStudentModal">
        Add Student
    </button>

    <!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">Add New Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="add_student_admin.php">
                    <div class="mb-3">
                        <label for="student_name" class="form-label">Student Name:</label>
                        <input type="text" class="form-control" id="student_name" name="student_name" required>
                    </div>
                    <script>
        <?php if (!empty($successMessage)) : ?>
            alert("<?php echo $successMessage; ?>");
        <?php endif; ?>
    </script>

                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender:</label>
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Transgender">Transgender</option>
                            <option value="Bisexual">Bisexual</option>
                            <option value="not to be specified">Not to be specified</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="birth_date" class="form-label">Birth Date:</label>
                        <input type="date" class="form-control" id="birth_date" name="birth_date" required>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address:</label>
                        <textarea class="form-control" id="address" name="address" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="course" class="form-label">Course:</label>
                        <input type="text" class="form-control" id="course" name="course" required>
                    </div>

                    <div class="mb-3">
                        <label for="block" class="form-label">Block:</label>
                        <input type="text" class="form-control" id="block" name="block" required>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status:</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="r">Regular</option>
                            <option value="i">Irregular</option>
                            <option value="g">Graduate</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success me-2">Add Student</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

    <table>
        <thead>
            <tr>
                <th>View</th>
                <th>Student name</th>
                <th>Course & Block</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><a href='view_student.php?id=" . $row['students_id'] . "'>View</a></td>";
                    echo "<td>" . $row['student_name'] . "</td>";
                    echo "<td>" . $row['course'] . " " . $row['block'] . "</td>";
                    echo "<td><a href='update_student.php?id=" . $row['students_id'] . "' class='update-btn'>Update</a>
                          <a href='delete_student.php?id=" . $row['students_id'] . "' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this student?\")'>Delete</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No students found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <footer>
        &copy; 2024 Student Management System. All rights reserved.
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const searchInput = document.getElementById("searchInput");
        const suggestionsBox = document.getElementById("suggestionsBox");
        const searchBtn = document.getElementById("searchBtn");

        let activeIndex = -1;

        const highlightText = (text, term) => {
            const regex = new RegExp(`(${term})`, 'gi');
            return text.replace(regex, "<strong>$1</strong>");
        };

        searchInput.addEventListener("input", async () => {
            const searchTerm = searchInput.value.trim();
            activeIndex = -1;

            if (searchTerm.length >= 2) {
                const response = await fetch(`search_suggestions.php?term=${searchTerm}`);
                const suggestions = await response.json();

                if (suggestions.length > 0) {
                    suggestionsBox.innerHTML = suggestions.map(suggestion => 
                        `<div class="suggestion-item" data-id="${suggestion.id}">
                            ${highlightText(suggestion.name, searchTerm)}
                        </div>`
                    ).join('');
                    suggestionsBox.style.display = 'block';
                } else {
                    suggestionsBox.innerHTML = `<div class="suggestion-item">No results found</div>`;
                    suggestionsBox.style.display = 'block';
                }
            } else {
                suggestionsBox.style.display = 'none';
            }
        });

        searchInput.addEventListener("keydown", (e) => {
            const items = document.querySelectorAll(".suggestion-item");
            if (e.key === "ArrowDown") {
                activeIndex = (activeIndex + 1) % items.length;
                setActive(items);
            } else if (e.key === "ArrowUp") {
                activeIndex = (activeIndex - 1 + items.length) % items.length;
                setActive(items);
            } else if (e.key === "Enter" && activeIndex >= 0) {
                items[activeIndex].click();
            }
        });

        const setActive = (items) => {
            items.forEach((item, index) => {
                item.classList.toggle("active", index === activeIndex);
            });
        };

        suggestionsBox.addEventListener("click", (e) => {
            const selectedSuggestion = e.target.closest(".suggestion-item");
            if (selectedSuggestion) {
                const studentId = selectedSuggestion.getAttribute('data-id');
                window.location.href = `view_student.php?id=${studentId}`;
            }
        });

        searchBtn.addEventListener("click", () => {
            const searchTerm = searchInput.value.trim();
            if (searchTerm) {
                window.location.href = `admin_page.php?search=${searchTerm}`;
            }
        });

        document.addEventListener("click", (e) => {
            if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                suggestionsBox.style.display = 'none';
            }
        });
    });
    </script>
</body>
</html>
