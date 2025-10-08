<?php include("db.php"); ?>

<h2>Register</h2>

<form method="POST" action="">
    <label>Username:</label>
    <input type="text" name="username" required> <br><br>

    <label>Email:</label>
    <input type="email" name="email" required> <br><br>

    <label>Password:</label>
    <input type="password" name="password" required> <br><br>

    <button type="submit" name="register">Register</button>
</form>

<?php
if (isset($_POST['register'])) {
    // Get and sanitize user inputs
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

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
    }

    $stmt->close();
    $insert_stmt->close();
}
?>
