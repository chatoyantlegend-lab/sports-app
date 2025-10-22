<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// Pending friend requests
$pending_sql = "
  SELECT f.id, u.username
  FROM friends f
  JOIN users u ON u.id = f.user_id
  WHERE f.friend_id = ? AND f.status = 'pending'
";
$stmt = $conn->prepare($pending_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$pending_requests = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Accepted friends
$friends_sql = "
  SELECT u.id, u.username
  FROM users u
  JOIN friends f ON (f.friend_id = u.id OR f.user_id = u.id)
  WHERE (f.user_id = ? OR f.friend_id = ?)
    AND f.status = 'accepted'
    AND u.id != ?
";
$stmt = $conn->prepare($friends_sql);
$stmt->bind_param("iii", $user_id, $user_id, $user_id);
$stmt->execute();
$friends = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Friends</title>
  <link rel="stylesheet" href="css/style2.css">
</head>
<body>

  <?php include("sidebar.php"); ?>
  <?php include("header.php"); ?>

  <div class="friends-main-content">
    <!-- Left Side: Friends Panel -->
    <div class="friends-sidebar">
      <h2>Friends</h2>

      <!-- Add Friend Form -->
      <form id="add-friend-form" method="POST" action="api/add_friend.php">
        <input type="text" name="friend_username" placeholder="Enter username to add" required>
        <button type="submit">Add Friend</button>
      </form>
      <div id="add-friend-message"></div>

      <!-- Pending Friend Requests -->
      <h3>Pending Friend Requests</h3>
      <div id="pending-requests">
        <?php if (empty($pending_requests)): ?>
          <p>No pending requests</p>
        <?php else: ?>
          <?php foreach ($pending_requests as $req): ?>
            <div class="friend-request" data-id="<?= $req['id'] ?>">
              <p><strong><?= htmlspecialchars($req['username']) ?></strong> sent you a friend request</p>
              <button class="accept-btn">Accept</button>
              <button class="reject-btn">Reject</button>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <!-- Accepted Friends -->
      <h3>Your Friends</h3>
      <div class="friends-scroll">
        <?php if (empty($friends)): ?>
          <p>You have no friends yet 😢</p>
        <?php else: ?>
          <?php foreach ($friends as $friend): ?>
            <div class="friends-avatar" data-id="<?= $friend['id'] ?>">
              <img src="images/default-avatar.png" alt="<?= htmlspecialchars($friend['username']) ?>">
              <p><?= htmlspecialchars($friend['username']) ?></p>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div> <!-- end friends-sidebar -->

    <!-- Right Side: Chat Window -->
    <div id="chat-container" class="chat-container" style="display:none;">
      <div class="chat-header">
        <span id="chat-with">Chat</span>
        <button id="close-chat" class="close-btn">✖</button>
      </div>

      <div id="chat-messages" class="chat-messages">
        <p class="no-messages" style="text-align:center; color:#999;">Select a friend to start chatting 💬</p>
      </div>

      <form id="chat-form" class="chat-input-area">
        <input type="text" id="chat-message" placeholder="Type a message..." autocomplete="off">
        <button type="submit">Send</button>
      </form>
    </div>
  </div> <!-- end friends-main-content -->

  <script>
  document.addEventListener("DOMContentLoaded", function() {
    const friends = document.querySelectorAll(".friends-avatar");
    const chatContainer = document.getElementById("chat-container");
    const chatWith = document.getElementById("chat-with");
    const closeChat = document.getElementById("close-chat");
    const chatMessages = document.getElementById("chat-messages");
    const chatForm = document.getElementById("chat-form");
    const chatInput = document.getElementById("chat-message");

    let activeFriendId = null;
    let activeFriendName = "";

    async function loadMessages() {
      if (!activeFriendId) return;
      try {
        const res = await fetch(`api/load_messages.php?friend_id=${activeFriendId}`);
        const data = await res.json();
        if (!data.success) return;

        chatMessages.innerHTML = "";
        if (!data.messages.length) {
          chatMessages.innerHTML = `<p class="no-messages" style="text-align:center; color:#999;">No messages yet. Start chatting!</p>`;
        }

        data.messages.forEach(msg => {
          const div = document.createElement("div");
          const isSent = Number(msg.sender_id) === Number(data.user_id);
          div.classList.add("message", isSent ? "sent" : "received");
          div.innerHTML = `
            ${msg.message}
            <span class="timestamp">
              ${new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
            </span>`;
          chatMessages.appendChild(div);
        });

        chatMessages.scrollTo({ top: chatMessages.scrollHeight, behavior: "smooth" });
      } catch (err) {
        console.error("Error loading messages:", err);
      }
    }

    // Open chat
    friends.forEach(friend => {
      friend.addEventListener("click", async () => {
        activeFriendId = friend.dataset.id;
        activeFriendName = friend.querySelector("p").textContent;
        chatWith.textContent = `Chat with ${activeFriendName}`;
        chatContainer.style.display = "flex";
        await loadMessages();
      });
    });

    // Send message
    chatForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const text = chatInput.value.trim();
      if (!text || !activeFriendId) return;

      await fetch("api/send_message.php", {
        method: "POST",
        body: new URLSearchParams({
          receiver_id: activeFriendId,
          message: text
        })
      });

      const bubble = document.createElement("div");
      bubble.classList.add("message", "sent");
      bubble.innerHTML = `
        ${text}
        <span class="timestamp">${new Date().toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})}</span>`;
      chatMessages.appendChild(bubble);
      chatMessages.scrollTop = chatMessages.scrollHeight;
      chatInput.value = "";
      setTimeout(loadMessages, 1000);
    });

    // Refresh
    setInterval(loadMessages, 4000);

    // Close chat
    closeChat.addEventListener("click", () => {
      chatContainer.style.display = "none";
      chatMessages.innerHTML = '<p class="no-messages" style="text-align:center; color:#999;">Select a friend to start chatting 💬</p>';
      activeFriendId = null;
    });

    // Add / accept / reject friends
    const form = document.getElementById("add-friend-form");
    form.addEventListener("submit", async e => {
      e.preventDefault();
      const res = await fetch(form.action, { method: "POST", body: new FormData(form) });
      const data = await res.json();
      document.getElementById("add-friend-message").textContent = data.message;
      form.reset();
    });

    document.querySelectorAll(".accept-btn, .reject-btn").forEach(btn => {
      btn.addEventListener("click", async () => {
        const id = btn.parentElement.dataset.id;
        const action = btn.classList.contains("accept-btn") ? "accept" : "reject";
        const res = await fetch("api/respond_friend.php", {
          method: "POST",
          body: new URLSearchParams({ request_id: id, action })
        });
        const data = await res.json();
        alert(data.message);
        location.reload();
      });
    });
  });
  </script>
</body>
</html>
