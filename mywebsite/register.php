<?php include("db.php"); ?>

<h2> Register </h2>
<form method ="POST" action="">
    <label> Username: </label>
    <input type ="text" name="username" required> <br></br>

    <label> Email: </label>
    <input type ="text" name="email" required> <br></br>

    <label> Password: </label>
    <input type="text" name="password" required> <br></br>

    <button type="submit" name="register"> Register</button>

</form>


<?php

$check_sql = "SELECT * FROM users WHERE username ='$username' OR email ='$email'"
$check_result = $conn->query($check_sql);

if($check_result->num_rows > 0) {
    echo "<p style='color:red;text-align:center;'>Username or Email already exists!</p>";
} else {
    //Hash the password before storing
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    //Insert into database
    $sql = "INSERT INTO users (username, email, password)
            VALUES ('$username', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        header("Location: login.php?signup=success");
        exit();
    }
  else {
    echo "<p style = 'color:red;'> Error " . $conn->error . "</p>";
}
}