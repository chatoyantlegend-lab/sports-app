<?php

$host = "localhost";
$user = "root"; //XAMPP DEFAULT
$pass = "";
$db = "myapp";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn -> connect_error)
{
    die("Connetion failed: ". $conn->connect_error);
}

?>