<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* =======================================
   1️⃣ Fetch accepted friends from database
   ======================================= */
$sql = "SELECT u.id, u.username, u.avatar
        FROM users u
        JOIN friend_requests f
          ON (f.sender_id = u.id OR f.receiver_id = u.id)
        WHERE (f.sender_id = ? OR f.receiver_id = ?)
          AND f.status = 'accepted'
          AND u.id != ?";
$stmt = $conn->prepare($sql);
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

    <style>
        .friends-main-content {
            margin-left: 260px;
            padding: 40px;
            background-color: #f5f5f5;
            min-height: 100vh;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .friends-scroll {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .friends-avatar {
            width: 100px;
            text-align: center;
            cursor: pointer;
            background: white;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }

        .friends-avatar img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ccc;
        }

        .friends-avatar p {
            margin-top: 5px;
            font-size: 14px;
            font-weight: bold;
        }

        .chat-box {
  margin-top: 25px;
  background: #ffffff;
  border-radius: 15px;
  box-shadow: 0 0 10px rgba(0,0,0,0.1);
  max-width: 700px;
  height: 450px;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.chat-messages {
  flex: 1;
  padding: 15px 20px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.chat-message {
  display: flex;
  flex-direction: column;
  max-width: 75%;
}

.chat-message.sent {
  align-self: flex-end;
}

.chat-message.received {
  align-self: flex-start;
}

.chat-message .bubble {
  background: #2d8cff;
  color: white;
  padding: 10px 15px;
  border-radius: 18px;
  font-size: 15px;
  line-height: 1.4;
  position: relative;
  word-wrap: break-word;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.chat-message.received .bubble {
  background: #e9e9eb;
  color: #000;
}

.time {
  display: block;
  font-size: 11px;
  opacity: 0.7;
  margin-top: 3px;
  text-align: right;
}

.chat-input {
  display: flex;
  gap: 10px;
  padding: 10px 15px;
  border-top: 1px solid #ddd;
  background: #fafafa;
}

.chat-input input {
  flex: 1;
  padding: 10px 14px;
  border: 1px solid #ccc;
  border-radius: 20px;
  font-size: 14px;
  outline: none;
  transition: 0.2s;
}

.chat-input input:focus {
  border-color: #2d8cff;
}

.chat-input button {
  padding: 10px 20px;
  border: none;
  border-radius: 20px;
  background: #2d8cff;
  color: white;
  cursor: pointer;
  font-weight: bold;
  transition: 0.2s;
}

.chat-input button:hover {
  background: #1b6ee6;
}

.no-messages {
  text-align: center;
  margin-top: 20px;
  color: #888;
  font-style: italic;
}


        /* Empty state text */
        .no-friends {
            font-style: italic;
            color: gray;
            margin-top: 10px;
        }

        /* Find friends section styling */
        .find-friends {
            margin-top: 40px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
            max-width: 700px;
        }

        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .search-form input {
            flex: 1;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #ccc;
        }

        .search-form button {
            background: #2d8cff;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            cursor: pointer;
        }

        .search-results {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .friend-card {
            background: #f9f9f9;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            width: 150px;
        }

        .friend-card img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <?php include("sidebar.php"); ?>

    <div class="friends-main-content">
        <h2>Friends</h2>

        <!-- =========================
             FIND FRIENDS SECTION
        ========================= -->
        <div class="find-friends">
            <h3>Find Friends</h3>
            <form method="GET" class="search-form">
                <input type="text" name="search" placeholder="Search username..." required>
                <button type="submit">Search</button>
            </form>

            <?php
            if (isset($_GET['search'])) {
                $search = '%' . $_GET['search'] . '%';
                $searchQuery = $conn->prepare("SELECT id, username, avatar FROM users WHERE username LIKE ? AND id != ?");
                $searchQuery->bind_param("si", $search, $user_id);
                $searchQuery->execute();
                $results = $searchQuery->get_result();

                if ($results->num_rows > 0) {
                    echo "<div class='search-results'>";
                    while ($row = $results->fetch_assoc()) {
                        echo "<div class='friend-card'>
                                <img src='".htmlspecialchars($row['avatar'])."' alt='avatar'>
                                <p>".htmlspecialchars($row['username'])."</p>
                                <button onclick='sendRequest(".$row['id'].")'>Add Friend</button>
                              </div>";
                    }
                    echo "</div>";
                } else {
                    echo "<p>No users found.</p>";
                }
            }
            ?>
        </div>

        <?php if (count($friends) === 0): ?>
            <p class="no-friends">You currently have no friends added. Send or accept a request to start chatting!</p>
        <?php else: ?>
            <div class="friends-scroll">
                <?php foreach ($friends as $friend): ?>
                    <div class="friends-avatar" onclick="openChat(<?= $friend['id'] ?>, '<?= htmlspecialchars($friend['username']) ?>')">
                        <img src="<?= htmlspecialchars($friend['avatar']) ?>" alt="">
                        <p><?= htmlspecialchars($friend['username']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div id="chat-container"></div>
    </div>

    <script>
    // ✅ 1️⃣ FIXED path for chat_box.php (now inside api folder)
    function openChat(friendId, friendName) {
        fetch(`api/chat_box.php?friend_id=${friendId}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById("chat-container").innerHTML = `
                <div class='chat-box'>
                    <h3>Chat with ${friendName}</h3>
                    <div class="chat-messages">${html}</div>
                    <div class="chat-input">
                        <input type="text" id="msgInput" placeholder="Type a message...">
                        <button onclick="sendMessage(${friendId})">Send</button>
                    </div>
                </div>`;
        });
    }

    // ✅ 2️⃣ FIXED path for sending messages
    function sendMessage(receiver) {
        const input = document.getElementById("msgInput");
        const message = input.value.trim();
        if (!message) return;

        fetch("api/send_message.php", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded"},
            body: `receiver=${receiver}&message=${encodeURIComponent(message)}`
        }).then(() => {
            input.value = "";
            openChat(receiver);
        });
    }

    // ✅ 3️⃣ FIXED path for sending friend requests
    function sendRequest(receiverId) {
        fetch("api/send_request.php", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded"},
            body: "receiver_id=" + receiverId
        })
        .then(res => res.text())
        .then(msg => alert(msg))
        .catch(err => console.error(err));
    }
    </script>
</body>
</html>
