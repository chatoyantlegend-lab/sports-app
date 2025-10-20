<?php
// api/update_event.php
session_start();
header('Content-Type: application/json; charset=utf-8');
if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success'=>false,'message'=>'Not logged']);
  exit;
}
$userId = (int) $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['success'=>false,'message'=>'Method']);
  exit;
}

$id = (int) ($_POST['id'] ?? 0);
if (!$id) {
  echo json_encode(['success'=>false,'message'=>'Missing id']);
  exit;
}

require_once '../db.php';

// Build dynamic update; allow updating title/start/duration/end
$fields = [];
$values = [];

if (isset($_POST['title'])) {
  $fields[] = 'title = ?'; $values[] = trim($_POST['title']);
}
if (isset($_POST['start'])) {
  $fields[] = 'start = ?'; $values[] = trim($_POST['start']);
}
if (isset($_POST['end'])) {
  $fields[] = 'end = ?'; $values[] = trim($_POST['end']);
}
if (isset($_POST['duration'])) {
  $fields[] = 'duration_minutes = ?'; $values[] = (int) $_POST['duration'];
}

if (empty($fields)) {
  echo json_encode(['success'=>false,'message'=>'Nothing to update']);
  exit;
}

$values[] = $id;
try {
  $sql = "UPDATE schedule SET " . implode(',', $fields) . " WHERE id = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute($values);
  echo json_encode(['success'=>true]);
} catch (Exception $e) {
  error_log($e->getMessage());
  echo json_encode(['success'=>false,'message'=>'DB error']);
}
