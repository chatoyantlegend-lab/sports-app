
<?php

session_start();
include("includes/db.php"); // make sure this creates $conn (mysqli)

// If user already logged in, send to profile
if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
    exit;
}

// When form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    

    // Basic validation & sanitization
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';
    

    $errors = [];

    if ($username === '') $errors[] = "Username is required."; 
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if ($password === '') $errors[] = "Password is required.";
    if ($password !== $confirm) $errors[] = "Passwords do not match.";
    

    if (empty($errors)) {
        // Hash the password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Prepared statement to insert user
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        if (!$stmt) {
            $errors[] = "Database error: " . $conn->error;
        } else {
            $stmt->bind_param("sss", $username, $email, $password_hash);
            if ($stmt->execute()) {
                // Get inserted user id
                $user_id = $conn->insert_id;

                // Save important info in session
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                

                // Redirect to profile (or wherever)
                header("Location: profile.php");
                exit;
            } else {
                // check duplicate email error
                if ($conn->errno === 1062) {
                    $errors[] = "That email is already registered.";
                } else {
                    $errors[] = "Database error: " . $conn->error;
                }
            }
            $stmt->close();
        }
    }
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

                <!--<label for="gender">Gender:</label>
                <select name="gender" id="gender" required>
                <option value="">Select gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
                </select> Maybe we add this later. -->

                <label>Password</label>
                <input type="password" name="password" placeholder="Create a password" required> <br><br>

                <label>Repeat Password</label>
                <input type="password" name="confirm_password" placeholder="Repeat your password" required><br></br>

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

