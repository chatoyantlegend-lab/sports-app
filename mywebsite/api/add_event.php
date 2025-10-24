<?php
// api/add_event.php
session_start();
header('Content-Type: application/json; charset=utf-8');
if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success'=>false,'message'=>'Not authenticated']);
  exit;
}
$userId = (int) $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['success'=>false,'message'=>'Method']);
  exit;
}

$title = trim($_POST['title'] ?? '');
$start = trim($_POST['start'] ?? '');
$duration = (int) ($_POST['duration'] ?? 60);

if (!$title || !$start) {
  echo json_encode(['success'=>false,'message'=>'Missing']);
  exit;
}

// compute end = start + duration minutes
$startDT = new DateTime($start);
$endDT = clone $startDT;
$endDT->modify("+{$duration} minutes");

require_once 'includes/db.php';

try {
  $stmt = $pdo->prepare("INSERT INTO schedule (user_id, title, start, end, duration_minutes) VALUES (?, ?, ?, ?, ?)");
  $stmt->execute([$userId, $title, $startDT->format('Y-m-d H:i:s'), $endDT->format('Y-m-d H:i:s'), $duration]);
  echo json_encode(['success'=>true, 'id' => $pdo->lastInsertId()]);
} catch (Exception $e) {
  error_log($e->getMessage());
  echo json_encode(['success'=>false,'message'=>'DB error']);
}
