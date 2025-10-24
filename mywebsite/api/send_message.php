<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['user_id'])) exit();

$sender = $_SESSION['user_id'];
$receiver = intval($_POST['receiver']);
$message = trim($_POST['message']);

if ($message === "") exit();

$stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $sender, $receiver, $message);
$stmt->execute();
