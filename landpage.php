<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f5f1; /* Light Pastel Beige */
            color: #333;
            line-height: 1.6;
        }

        /* Header */
        header {
            background-color: #a8d5e2; /* Pastel Blue */
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        header .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: #5b5f97; /* Pastel Purple */
        }

        /* Hero Section */
        .hero {
            text-align: center;
            padding: 50px 20px;
        }

        .hero h1 {
            font-size: 2.5rem;
            color: #5b5f97; /* Pastel Purple */
            margin-bottom: 15px;
        }

        .hero p {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 25px;
        }

        .hero a {
            text-decoration: none;
            background-color: #f4c7ab; /* Pastel Peach */
            color: #fff;
            padding: 10px 25px;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .hero a:hover {
            background-color: #f8b195; /* Slightly darker pastel peach */
        }

        /* Login/Sign-Up Section */
        .auth-buttons {
            text-align: center;
            padding: 50px 20px;
            display: none;
            flex-direction: column;
            gap: 15px;
        }

        .auth-buttons a {
            text-decoration: none;
            background-color: #f4c7ab; /* Pastel Peach */
            color: #fff;
            padding: 12px 20px;
            border-radius: 5px;
            font-size: 1.1rem;
            font-weight: bold;
            width: 80%;
            max-width: 300px;
            margin: 0 auto;
            transition: background-color 0.3s ease;
        }

        .auth-buttons a:hover {
            background-color: #f8b195; /* Slightly darker pastel peach */
        }

        /* Footer */
        footer {
            background-color: #a8d5e2; /* Pastel Blue */
            color: #333;
            text-align: center;
            padding: 15px 0;
            margin-top: 20px;
            font-size: 0.9rem;
        }

        footer a {
            color: #5b5f97; /* Pastel Purple */
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Student Management System</div>
    </header>

    <section class="hero">
        <h1>Welcome to the Student Management System</h1>
        <p>Easily manage student records, track performance, and simplify processes.</p>
        <a href="#" id="get-started-btn">Get Started</a>
    </section>

    <section class="auth-buttons" id="auth-section">
        <a href="login.php">Login</a>
        <a href="signup.php">Sign Up</a>
    </section>

    <footer>
        <p>&copy; 2024 Student Management System. All Rights Reserved. | <a href="#">Privacy Policy</a></p>
    </footer>

    <script>
        // Show login/signup section when "Get Started" is clicked
        const getStartedBtn = document.getElementById('get-started-btn');
        const authSection = document.getElementById('auth-section');

        getStartedBtn.addEventListener('click', (event) => {
            event.preventDefault(); // Prevent default anchor behavior
            authSection.style.display = 'flex';
            getStartedBtn.style.display = 'none'; // Hide the "Get Started" button
        });
    </script>
</body>
</html>
