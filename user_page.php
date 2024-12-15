<?php
session_start();
include 'server/connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'u') {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];

$query_admin = "SELECT full_name, image FROM users WHERE user_id = '$admin_id' AND user_type = 'u'";
$result_admin = mysqli_query($conn, $query_admin);

if ($result_admin && mysqli_num_rows($result_admin) === 1) {
    $admin_data = mysqli_fetch_assoc($result_admin);
    $admin_name = $admin_data['full_name'];
    $admin_image = !empty($admin_data['image']) ? $admin_data['image'] : 'default.png';
} else {
    echo "Admin not found!";
    exit();
}

$query_admin = "SELECT * FROM users WHERE user_id = '$admin_id' AND user_type = 'u'";
$result_admin = mysqli_query($conn, $query_admin);
if (!$result_admin || mysqli_num_rows($result_admin) != 1) {
    echo "Admin not found!";
    exit();
}

$filterCourse = isset($_GET['filterCourse']) ? trim($_GET['filterCourse']) : '';
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$sql = "SELECT students_id, student_name, course, status, year, block FROM students";
if ($searchTerm) {
    $sql .= " WHERE student_name LIKE '%" . $conn->real_escape_string($searchTerm) . "%' 
              OR course LIKE '%" . $conn->real_escape_string($searchTerm) . "%' 
              OR block LIKE '%" . $conn->real_escape_string($searchTerm) . "%'";
}
if ($filterCourse) {
    $conditions[] = "course = '" . $conn->real_escape_string($filterCourse) . "'";
}
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
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
    <link rel="stylesheet" href="css/user_page.css">
</head>
<body>

<div class="nav">
    <div class="student-ms">
        <h3 class="sm-system">BU Polangui <span class="sistema">Student Management System</span></h3>
    </div>
    
    <div class="user-account">
        <div class="dropdown">

            <button class="dropbtn">
                <img src="image/<?= htmlspecialchars($admin_image) ?>" alt="Profile" class="profile-icon">
            </button>
            
            <div class="dropdown-content">
                <p><?= htmlspecialchars($admin_name) ?></p>
                <a href="user_manage_account.php">Manage Account</a>
                <a href="logout.php">Logout</a>
            </div>
            
        </div>
    </div>
</div>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="success-message">
        <?= htmlspecialchars($_SESSION['success_message']); ?>
    </div>
    <?php unset($_SESSION['success_message']); // Remove message after displaying ?>
<?php endif; ?>


<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($_SESSION['error_message']) ?>
    </div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>


    <div class="profile">
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search...">
            <button id="searchBtn">Search</button>
            <div id="suggestionsBox" class="suggestions" style="display: none;"></div>
        </div>

        <div class="filter-course">
            <form method="GET" action="">
                <select id="filterCourse" name="filterCourse" onchange="this.form.submit()">
                    <option value="">All Courses</option>
                    <option value="BSIT" <?= isset($_GET['filterCourse']) && $_GET['filterCourse'] == 'BSIT' ? 'selected' : '' ?>>BSIT</option>
                    <option value="BSIS" <?= isset($_GET['filterCourse']) && $_GET['filterCourse'] == 'BSIS' ? 'selected' : '' ?>>BSIS</option>
                    <option value="BSCS" <?= isset($_GET['filterCourse']) && $_GET['filterCourse'] == 'BSCS' ? 'selected' : '' ?>>BSCS</option>
                    <option value="BSIT - Animation" <?= isset($_GET['filterCourse']) && $_GET['filterCourse'] == 'BSIT - Animation' ? 'selected' : '' ?>>BSIT - Animation</option>
                </select>
            </form>
        </div>

        <div class="add-student-btn">
            <button class="add-btn" id="openAddModal">Add Student</button>
        </div>
    </div>

    <div class="table-dis">
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Course, Yr & Block</th>
                    <th>Status</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['student_name'] . "</td>";
                        echo "<td>" . $row['course'] . " " . $row['year'] . $row['block'] . "</td>";
                        echo "<td>";
                            if ($row['status'] == 'r') {
                                echo "Regular";
                            } elseif ($row['status'] == 'i') {
                                echo "Irregular";
                            } elseif ($row['status'] == 'g') {
                                echo "Graduate";
                            } else {
                                echo "Unknown"; // In case there is an unexpected value
                            }

                            echo "</td>";
                        echo "<td>
                              <button class='view-btn' onclick='openStudentProfile(" . $row['students_id'] . ")'>View</button>
                            </td>";                      
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No students found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div id="addStudentModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" id="closeAddModal">&times;</span>
            <h2>Add New Student</h2>
            <form action="user_add_student.php" method="POST">
                <label for="student_name">Student Name:</label>
                <input type="text" id="student_name" name="student_name" required>

                <label for="contact_number">Contact Number:</label>
                <input type="text" id="contact_number" name="contact_number" required>

                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>

                <label for="birth_date">Birth Date:</label>
                <input type="date" id="birth_date" name="birth_date" required>

                <label for="course">Course:</label>
                <select id="course" name="course" required>
                    <option value="BSIT">BSIT</option>
                    <option value="BSIS">BSIS</option>
                    <option value="BSCS">BSCS</option>
                    <option value="BSIT - Animation">BSIT - Animation</option>
                </select>

                <label for="year">Year:</label>
                <select id="year" name="year" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>

                <label for="block">Block:</label>
                <select id="block" name="block" required>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>

                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="r">Regular</option>
                    <option value="i">Irregular</option>
                    <option value="g">Graduate</option>
                </select>
                
                <button type="submit" class="modal-submit-btn">Add Student</button>
            </form>
        </div>
    </div>


    <div id="studentProfileModal" class="modal">
    <div class="modal-content profile-modal">
        <span class="close-btn" id="closeProfileModal">&times;</span>
        <h2>Student Profile</h2>
        <div class="student-profile-container">
            <div class="profile-right">
                <p><strong>Name:</strong> <span id="profileName"></span></p>
                <p><strong>Course:</strong> <span id="profileCourse"></span></p>
                <p><strong>Year & Block:</strong> <span id="profileYearBlock"></span></p>
                <p><strong>Contact:</strong> <span id="profileContact"></span></p>
                <p><strong>Gender:</strong> <span id="profileGender"></span></p>
                <p><strong>Address:</strong> <span id="profileAddress"></span></p>
                <p><strong>Birth Date:</strong> <span id="profileBirthDate"></span></p>
                <p><strong>Status:</strong> <span id="profileStatus"></span>
            </p>
            </div>
        </div>
    </div>
</div>



    <footer>
        &copy; 2024 Bicol University Polangui Student Management System.
    </footer>

    <script src="js/user_search.js"></script>
    <script src="js/modal.js"></script>
    <script src="js/viewModal.js"></script>



</body>
</html>
