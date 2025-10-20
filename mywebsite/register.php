<?php 
session_start();
include("includes/db.php");
$_SESSION['username'] = $username;
$_SESSION['user_id'] = $user_id;


if (isset($_POST['register'])) {
    // Get and sanitize user inputs
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $repeat_password = trim($_POST['repeat_password']);

    // Check if passwords match
    if ($password !== $repeat_password) {
        echo "<p style='color:red; text-align:center;'>Passwords do not match!</p>";
        exit();
    }

    // Check if username or email already exists
    $check_sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $check_result = $stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<p style='color:red; text-align:center;'>Username or Email already exists!</p>";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $insert_sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($insert_stmt->execute()) {
            header("Location: login.php?signup=success");
            exit();
        } else {
            echo "<p style='color:red; text-align:center;'>Error: " . $conn->error . "</p>";
        }

        $insert_stmt->close();
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - MySportsApp</title>
    <link rel="stylesheet" href="css/style3.css">
</head>
<body>
    <div class="register-hero">
        <div class ="register-card">
            <h1>Welcome to <span class="brand">MySportsApp</span>!</h1>
            <p class="motto">"Train. Compete. Conquer."</p>

            <?php if (isset($error)) echo "<p class='error-msg'>$error</p>"; ?>
            <?php if (isset($success)) echo "<p class='succes-msg'>$success</p>"; ?>

            <!-- Register Form -->

            <form method="POST" action="" class="register-form">
                <label>Username</label>
                <input type="text" name="username" placeholder="Choose a username" required> <br><br>

                <label>Email</label>
                <input type="email" name="email" placeholder="Enter your email" required> <br><br>

                <label>Password</label>
                <input type="password" name="password" placeholder="Create a password" required> <br><br>

                <label>Repeat Password</label>
                <input type="password" name="repeat_password" placeholder="Repeat your password" required><br></br>

                <button type="submit" name="register">Sign Up</button>
            </form> 

            <p class="login-link">Already have an account? <a href="login.php">Login Here</a>.</p>
        </div>
    </div>


<footer class="register-footer">
    &copy; <?= date("Y") ?> MySportsapp. All rights reserved.

</footer>
</body>
</html>

