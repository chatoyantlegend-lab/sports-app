<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'message' => 'Not logged in']);
  exit;
}

$sender_id = $_SESSION['user_id'];
$receiver_id = intval($_POST['receiver_id'] ?? 0);
$message = trim($_POST['message'] ?? '');

if (!$receiver_id || !$message) {
  echo json_encode(['success' => false, 'message' => 'Missing fields']);
  exit;
}

$stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $sender_id, $receiver_id, $message);
$stmt->execute();

echo json_encode(['success' => true, 'message' => 'Message sent!']);
?>
