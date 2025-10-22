<?php

session_start(); // Start session to remember logged-in user
include("includes/db.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        // ✅ Store user info in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['description'] = $user['description'];

        header("Location: profile.php");
        exit;
    } else {
        echo "Invalid email or password.";
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style3.css">
</head>
<body>
    <div class="login-container">

        <!-- Logo -->
        <div class="logo">
            <img src="images/logo.png" alt="Logo">
        </div>

        <!-- Centered Main content -->
        <div class="login-main">
            <!-- Left Image-->
            <div class ="login-image">
                <img src="images/login-illustration.png" alt="Illustration">
            </div>

            <!-- Right Form -->
            <div class="login-form">
                <h2>Login</h2>
                <form method="POST" action="">
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" name="login">Login</button>
                </form>
                <p>Don't have an account? <a href="register.php">Sign Up</a></p>
            </div>
        </div>

            <!-- Footer -->
            <footer>
                <p>&copy; 2025 MyWebsite. All Rights reserved. | <a href="#">Privacy Policy</a> | <a href="#"> Terms</a></p>
            </footer>

        </div>
</body>
</html>


