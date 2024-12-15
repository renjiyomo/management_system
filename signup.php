<?php
// Include the database connection
include 'server/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user_type = 'u';

    // Check if username or email already exists
    $checkQuery = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    $checkQuery->bind_param("ss", $email, $username);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows > 0) {
        $error = "Username or Email already exists!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, username, password, user_type) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $full_name, $email, $username, $password, $user_type);

        if ($stmt->execute()) {
            $success = "Registration successful! <a href='login.php'>Login here</a>";
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    $checkQuery->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/signup.css">

</head>
<body>
    <div class="form-container">
        <h2>Sign Up</h2>
        <?php if (isset($error)): ?>
            <p class="message"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p class="message" style="color: green;"><?php echo $success; ?></p>
        <?php endif; ?> 
        <form action="signup.php" method="POST">

        <div class="form-group">
            <input type="text" name="full_name" placeholder="Full Name" required><br>
        </div>

        <div class="form-group">   
            <input type="email" name="email" placeholder="Email" required><br>
        </div>

        <div class="form-group">   
            <input type="text" name="username" placeholder="Username" required><br>
        </div>
          
        <div class="form-group">    
            <input type="password" name="password" placeholder="Password" required><br>
        </div>

        <div class="form-group">    
            <button type="submit">Register</button>
        </div>

        <div class="form-group login-text">
                <p class="dont-acc">Already have an account? <a href="/management_system/login.php">Login</a></p>
            </div>

        </form>
    </div>
</body>
</html>
