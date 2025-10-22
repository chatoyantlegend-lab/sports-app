<?php
// api/delete_event.php
session_start();
header('Content-Type: application/json; charset=utf-8');
if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success'=>false,'message'=>'Not logged']);
  exit;
}

$id = (int) ($_POST['id'] ?? 0);
if (!$id) { echo json_encode(['success'=>false,'message'=>'Missing']); exit; }
require_once 'includes/db.php';

try {
  $stmt = $pdo->prepare("DELETE FROM schedule WHERE id = ?");
  $stmt->execute([$id]);
  echo json_encode(['success'=>true]);
} catch (Exception $e) {
  error_log($e->getMessage());
  echo json_encode(['success'=>false,'message'=>'DB error']);
}
