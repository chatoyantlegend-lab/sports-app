<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['user_id'])) {
  http_response_code(403);
  echo json_encode([]);
  exit;
}

$user_id = $_SESSION['user_id'];


// Fetch
$query = "SELECT workout, date, duration FROM workouts WHERE user_id='$user_id'";
$result = mysqli_query($conn, $query);

$events = [];
while ($row = mysqli_fetch_assoc($result)) {
  $events[] = [
    'title' => $row['workout'] . ' (' . $row['duration'] . ' mins)',
    'start' => $row['date'],
    'allDay' => true
  ];
}

header('Content-Type: application/json');
echo json_encode($events);
?>
