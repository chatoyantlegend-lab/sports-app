<?php
include("db.php");
session_start(); // Start session to remember logged-in user

?>

<h2>Login</h2>
<form method ="POST" action="">

	<label>Email:</label>
	<input type="email" name="email" required><br></br>

	<label>Password:</label>
	<input type="password" name="password" required><br></br>

	<button type="submit" name="login">Login</button>

</form>

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