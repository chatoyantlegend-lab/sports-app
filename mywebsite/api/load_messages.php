<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'message' => 'Not logged in']);
  exit;
}

$user_id = $_SESSION['user_id'];
$friend_id = intval($_GET['friend_id'] ?? 0);

if (!$friend_id) {
  echo json_encode(['success' => false, 'message' => 'Missing friend id']);
  exit;
}

// ✅ FIX: include both directions (you → friend OR friend → you)
$sql = "
  SELECT 
    m.id,
    m.sender_id,
    m.receiver_id,
    m.message,
    m.created_at,
    u.username AS sender_name
  FROM messages m
  JOIN users u ON u.id = m.sender_id
  WHERE (m.sender_id = ? AND m.receiver_id = ?)
     OR (m.sender_id = ? AND m.receiver_id = ?)
  ORDER BY m.created_at ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $user_id, $friend_id, $friend_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
  $messages[] = $row;
}

// ✅ Always return the logged-in user ID (used by JS)
echo json_encode(['success' => true, 'user_id' => $user_id, 'messages' => $messages]);
?>
