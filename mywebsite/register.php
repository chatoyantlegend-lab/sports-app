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

if(isset($_POST['register'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    //Hash the password before storing
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    //Insert into database
    $sql = "INSERT INTO users (username, email, password)
            VALUES ('$username', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "<p style = 'color:green;'> Registered succesfully! </p>";
    }
  else {
    echo "<p style = 'color:red;'> Error " . $conn->error . "</p>";
}
}