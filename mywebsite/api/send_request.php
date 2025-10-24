<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['user_id']) || !isset($_POST['receiver_id'])) {
    echo "Not logged in or missing data.";
    exit();
}

$sender_id = $_SESSION['user_id'];
$receiver_id = intval($_POST['receiver_id']);

// Prevent duplicate requests or sending to self
if ($sender_id === $receiver_id) {
    echo "You cannot add yourself.";
    exit();
}

$check = $conn->prepare("
    SELECT * FROM friend_requests
    WHERE (sender_id = ? AND receiver_id = ?)
       OR (sender_id = ? AND receiver_id = ?)
");
$check->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "Request already exists.";
} else {
    $stmt = $conn->prepare("INSERT INTO friend_requests (sender_id, receiver_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $sender_id, $receiver_id);
    $stmt->execute();
    echo "Request sent!";
}
?>
