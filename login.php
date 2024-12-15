<?php
session_start();
include 'server/connection.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['login_password']);

    $query_users = "SELECT * FROM users WHERE username='$username'";
    $result_users = mysqli_query($conn, $query_users);

    if ($result_users && mysqli_num_rows($result_users) == 1) {
        $user = mysqli_fetch_assoc($result_users);

        // Check if the password matches
        if ($password === $user['password']) {
            // Store user information in session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_type'] = $user['user_type'];

            // Redirect based on user type
            if ($user['user_type'] == 'a') {
                // If user is an admin, redirect to admin_page.php
                header("Location: admin_page.php");
            } elseif ($user['user_type'] == 'u') {
                // If user is a regular user, redirect to user_page.html
                header("Location: user_page.php");
            }
            exit;
        } else {
            $message = "Invalid password!";
        }
    } else  {
        $message = "No user found with this username!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

<div class="form-box">
    <div class="form-section active">
        <h3>SIGN <span>IN</span></h3>

        <?php if (!empty($message)): ?>
                <p class="message error auto-hide"><?php echo $message; ?></p>
            <?php endif; ?>
            
        <form action="" method="POST">
            <div class="form-group">
                <input type="username" id="username" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="password" id="login_password" name="login_password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <button type="submit">LOGIN</button>
            </div>
            <div class="form-group login-text">
                <p class="dont-acc">Don't have an account? <a href="/management_system/signup.php">Sign up now</a></p>
            </div>
        </form>
    </div>
</div>
</body>
</html>
