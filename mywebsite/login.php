<?php
include("includes/db.php");
session_start(); // Start session to remember logged-in user
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


<?php
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check user in DB
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify hashed password
        if (password_verify($password, $row['password'])) {
            $_SESSION['user'] = $row['username']; // store username in session
            header("Location: profile.php"); // redirect to profile
            exit();
        } else {
            echo "<p style='color:red;'>Incorrect password!</p>";
        }
    } else {
        echo "<p style='color:red;'>No account found with that email!</p>";
    }
}
?>