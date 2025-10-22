<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'message' => 'Not logged in']);
  exit;
}

$user_id = $_SESSION['user_id'];
$request_id = intval($_POST['request_id'] ?? 0);
$action = $_POST['action'] ?? '';

if (!$request_id || !in_array($action, ['accept', 'reject'])) {
  echo json_encode(['success' => false, 'message' => 'Invalid request']);
  exit;
}

$status = $action === 'accept' ? 'accepted' : 'rejected';

// Only friend_id can respond
$stmt = $conn->prepare("UPDATE friends SET status=? WHERE id=? AND friend_id=? AND status='pending'");
$stmt->bind_param("sii", $status, $request_id, $user_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
  echo json_encode(['success' => true, 'message' => 'Friend request ' . $status]);
} else {
  echo json_encode(['success' => false, 'message' => 'No pending request found']);
}
?>
