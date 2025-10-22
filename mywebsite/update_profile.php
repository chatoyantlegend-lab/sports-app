<?php
session_start();
include("includes/db.php");

//Make sure user is logged in

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get form inputs safely
$username = myaqli_real_escape_string($conn, $_POST['username'])
$description = mysqli_real_escape_string($conn, $_POST['decription']);
$gender = mysqli_real_escape_string($conn, $_POST['gender']);

// Update Database

$query = "UPDATE users SET username='$username', descripton ='$description' WHERE id='$user_id'";
$result = mysqli_query($conn, $query);

if ($result) {

    $_SESSION['username'] = $username;
    $_SESSION['description'] = $description;
    $_SESSION['gender'] = $gender;

    header("Location: profile.php?updated=1");
    exit;
} else {
    echo "Error updating profile: " . mysqli_error($conn);
}
?>