<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'message' => 'Not logged in']);
  exit;
}

$user_id = $_SESSION['user_id'];

// Fetch pending friend requests
$sql = "
  SELECT f.id, u.username
  FROM friends f
  JOIN users u ON u.id = f.user_id
  WHERE f.friend_id = ? AND f.status = 'pending'
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$requests = [];
while ($row = $result->fetch_assoc()) {
  $requests[] = $row;
}

echo json_encode([
  'success' => true,
  'count' => count($requests),
  'requests' => $requests
]);
?>
