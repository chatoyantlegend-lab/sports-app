<?php
// api/get_events.php
session_start();
header('Content-Type: application/json; charset=utf-8');
if (!isset($_SESSION['user_id'])) {
  echo json_encode([]);
  exit;
}
$userId = (int) $_SESSION['user_id'];

require_once 'includes/db.php'; // put PDO connection in db.php

try {
  $stmt = $pdo->prepare("SELECT id, title, start, end FROM schedule WHERE user_id = ? ORDER BY start");
  $stmt->execute([$userId]);
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // FullCalendar can accept array of { id, title, start, end }
  echo json_encode($rows);
} catch (Exception $e) {
  error_log($e->getMessage());
  echo json_encode([]);
}
