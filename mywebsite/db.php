<?php

$host = "localhost";
$user = "root"; //XAMPP DEFAULT
$pass = "";
$db = "myapp";
$charset= 'utf8mb4';
$dsn="mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn,$user, $pass, $options)
} catch (Exception $e) {
    error_log('DB connection error: '. $e->getMessage());
    throw $e;
}

$conn = new mysqli($host, $user, $pass, $db);

if ($conn -> connect_error)
{
    die("Connetion failed: ". $conn->connect_error);
}

?>