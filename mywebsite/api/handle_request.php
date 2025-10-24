<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['user_id']) || !isset($_POST['request_id']) || !isset($_POST['action'])) {
    echo "Missing data.";
    exit();
}

$request_id = intval($_POST['request_id']);
$action = $_POST['action'];

if ($action === "accept") {
    $conn->query("UPDATE friend_requests SET status='accepted' WHERE id=$request_id");
    echo "Friend request accepted!";
} elseif ($action === "decline") {
    $conn->query("UPDATE friend_requests SET status='declined' WHERE id=$request_id");
    echo "Friend request declined.";
} else {
    echo "Invalid action.";
}
?>
