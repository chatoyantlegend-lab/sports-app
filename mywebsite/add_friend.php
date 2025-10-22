<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'message' => 'Not logged in']);
  exit;
}

$user_id = $_SESSION['user_id'];
$friend_username = trim($_POST['friend_username'] ?? '');

if (!$friend_username) {
  echo json_encode(['success' => false, 'message' => 'Missing username']);
  exit;
}

// Find friend user
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $friend_username);
$stmt->execute();
$res = $stmt->get_result();
$friend = $res->fetch_assoc();

if (!$friend) {
  echo json_encode(['success' => false, 'message' => 'User not found']);
  exit;
}

$friend_id = $friend['id'];

if ($friend_id == $user_id) {
  echo json_encode(['success' => false, 'message' => "You can't add yourself"]);
  exit;
}

// Check if request already exists
$check = $conn->prepare("SELECT * FROM friends 
                         WHERE (user_id=? AND friend_id=?) 
                            OR (user_id=? AND friend_id=?)");
$check->bind_param("iiii", $user_id, $friend_id, $friend_id, $user_id);
$check->execute();
if ($check->get_result()->num_rows > 0) {
  echo json_encode(['success' => false, 'message' => 'Friend request already exists']);
  exit;
}

// Create new pending request
$insert = $conn->prepare("INSERT INTO friends (user_id, friend_id, status) VALUES (?, ?, 'pending')");
$insert->bind_param("ii", $user_id, $friend_id);
$insert->execute();

echo json_encode(['success' => true, 'message' => 'Friend request sent!']);
?>
