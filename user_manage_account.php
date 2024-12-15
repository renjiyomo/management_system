<?php
session_start();
require_once 'server/connection.php'; // Include your database connection script

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$query = "SELECT full_name, email, username, password, image FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Account</title>
    <link rel="stylesheet" href="css/manageAccount.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Manage Account</h1>
        </div>

        <div class="profile-image">
            <img src="image/<?php echo htmlspecialchars($user['image']); ?>" alt="Profile Picture" id="profilePreview">
            <label for="profileImage">Change Profile Image</label>
            <input type="file" id="profileImage" name="image" accept="image/*" form="accountForm">
        </div>

        <form id="accountForm" action="user_update_account.php" method="POST" enctype="multipart/form-data">
            <div>
                <label for="fullName">Full Name</label>
                <input type="text" id="fullName" name="fullName" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
            </div>

            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <div>
                <label for="username">Username</label>
                <input type="username" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>

            <div>
                <label for="password">Password</label>
                <div style="position: relative; display: inline-block; width: 100%;">
                    <input type="password" id="password" name="password"  value="<?php echo htmlspecialchars($user['password']); ?>">
                    <span id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                        show
                    </span>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit">Save Changes</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='user_page.php'">Cancel</button>
            </div>
        </form>
    </div>

    <script>
        const profileImageInput = document.getElementById('profileImage');
        const profilePreview = document.getElementById('profilePreview');

        profileImageInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    profilePreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>


<script>
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Toggle eye icon
        this.textContent = type === 'password' ? 'show' : 'hide';
    });
</script>

</body>
</html>
