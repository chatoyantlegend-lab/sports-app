<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['user_id'])) {
    die("Not logged in");
}

$user_id = $_SESSION['user_id'];
$workout = $_POST['workout'] ?? '';
$duration = $_POST['duration'] ?? '';
$date = $_POST['date'] ?? '';

if ($workout && $date) {
    $stmt = $conn->prepare("INSERT INTO workouts (user_id, workout, duration, date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isis", $user_id, $workout, $duration, $date);
    $stmt->execute();
    echo "Workout saved!";
} else {
    echo "Missing required fields.";
}
?> 
