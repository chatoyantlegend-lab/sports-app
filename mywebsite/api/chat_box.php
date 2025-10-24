<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['user_id']) || !isset($_GET['friend_id'])) {
    exit("Missing user or friend ID.");
}

$user_id = $_SESSION['user_id'];
$friend_id = intval($_GET['friend_id']);

$query = $conn->prepare("
    SELECT sender_id, receiver_id, message, sent_at 
    FROM messages 
    WHERE (sender_id = ? AND receiver_id = ?) 
       OR (sender_id = ? AND receiver_id = ?)
    ORDER BY sent_at ASC
");
$query->bind_param("iiii", $user_id, $friend_id, $friend_id, $user_id);
$query->execute();
$messages = $query->get_result();

if ($messages->num_rows === 0) {
    echo "<p class='no-messages'>No messages yet — start the conversation!</p>";
} else {
    while ($msg = $messages->fetch_assoc()) {
        $isSender = $msg['sender_id'] == $user_id;
        $time = date("H:i", strtotime($msg['sent_at'])); // 24-hour format (HH:mm)
        ?>
        <div class="chat-message <?= $isSender ? 'sent' : 'received' ?>">
            <div class="bubble">
                <?= nl2br(htmlspecialchars($msg['message'])) ?>
                <span class="time"><?= $time ?></span>
            </div>
        </div>
        <?php
    }
}
?>
