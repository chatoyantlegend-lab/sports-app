<?php
session_start();
include("includes/db.php");

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  echo json_encode(["error" => "Not logged in"]);
  exit;
}

$user_id = (int) $_SESSION['user_id'];

$sql = "SELECT id, workout, start, end, sets, reps 
        FROM workouts 
        WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$events = [];
while ($row = $result->fetch_assoc()) {
  // If end is empty or equals start, add +1 hour so event shows visibly
  $start = date('c', strtotime($row['start']));
  $end = $row['end'] && $row['end'] != $row['start']
    ? date('c', strtotime($row['end']))
    : date('c', strtotime($row['start'] . ' +1 hour'));

  $events[] = [
    'id' => $row['id'],
    'title' => $row['workout'] . " ({$row['sets']}x{$row['reps']})",
    'start' => $start,
    'end' => $end,
    'allDay' => false
  ];
}

echo json_encode($events);
?>
