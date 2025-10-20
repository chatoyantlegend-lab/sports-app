<?php
session_start();
include("includes/db.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Temporary hardcoded friend & message data
$friends = [
    ["username" => "alex", "avatar" => "images/alex.jpg"],
    ["username" => "jordan", "avatar" => "images/jordan.jpg"],
    ["username" => "maria", "avatar" => "images/maria.jpg"],
];

$messages = [
    ["username" => "alex", "avatar" => "images/alex.jpg", "text" => "Hey! How's it going?", "time" => "2h ago"],
    ["username" => "jordan", "avatar" => "images/jordan.jpg", "text" => "Let's hit a workout session tomorrow!", "time" => "5h ago"],
    ["username" => "maria", "avatar" => "images/maria.jpg", "text" => "Did you check my latest post?", "time" => "1d ago"],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Friends</title>
    <link rel="stylesheet" href="css/style2.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <img src="images/logo.png" alt="Logo">
        </div>

        <ul class="menu">
            <li id="btn-profile"><a href="profile.php"><img src="images/profile.png" alt="Profile">Profile</a></li>
            <li id="btn-activity"><a href="activity.php"><img src="images/activity.png" alt="Activity">Activity</a></li>
            <li id="btn-friends"><a href="friends.php"><img src="images/friends.png" alt="Friends">Friends</a></li>
            <li id="btn-schedule"><a href="schedule.php"><img src="images/schedule.png" alt="Schedule">Schedule</a></li>
        </ul>
    </div>

    <div class="friends-main-content">
        <h2>Friends</h2>

        <!-- Top Scroll Section -->
        <div class="friends-scroll">
            <?php foreach ($friends as $friend): ?>
                <div class="friends-avatar">
                    <img src="<?= htmlspecialchars($friend['avatar']) ?>" alt="<?= htmlspecialchars($friend['username']) ?>">
                    <p><?= htmlspecialchars($friend['username']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Message List Section --> 
        <div class="message-list">
            <?php foreach ($messages as $msg): ?>
                <div class="message-item">
                    <img src="<?= htmlspecialchars($msg['avatar']) ?>" class="msg-avatar" alt="<?= htmlspecialchars($msg['username']) ?>">
                    <div class="msg-content">
                        <div class="msg-header">
                            <span class="msg-username"><?= htmlspecialchars($msg['username']) ?></span>
                            <span class="msg-time"><?= htmlspecialchars($msg['time']) ?></span>
                        </div>
                        <p class="msg-text"><?= htmlspecialchars($msg['text']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>

    document.addEventListener("DOMContentLoaded", function() {
  // Get current page file name (like "schedule.php")
  const currentPage = window.location.pathname.split("/").pop();

  // Select all sidebar links
  const links = document.querySelectorAll(".sidebar a");

  links.forEach(link => {
    if (link.getAttribute("href") === currentPage) {
      link.classList.add("active");
    }
  });
});

</script>
</body>
</html>
