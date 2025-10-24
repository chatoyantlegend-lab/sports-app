<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['user_id'])) {
  echo "Not logged in";
  exit;
}

$user_id = $_SESSION['user_id'];
$workout = $_POST['workout'] ?? '';
$date = $_POST['date'] ?? '';
$start_time = $_POST['start_time'] ?? '';
$end_time = $_POST['end_time'] ?? '';
$sets = $_POST['sets'] ?? 0;
$reps = $_POST['reps'] ?? 0;

$start = $date . ' ' . $start_time;
$end = $date . ' ' . $end_time;

$stmt = $conn->prepare("INSERT INTO workouts (user_id, workout, start, end, sets, reps) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssii", $user_id, $workout, $start, $end, $sets, $reps);

if ($stmt->execute()) {
  echo "Workout saved";
} else {
  echo "Error: " . $conn->error;
}
?>
