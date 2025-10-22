<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$query = "SELECT username, description FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile</title>
  <link rel="stylesheet" href="css/style2.css">
</head>
<body>

  <?php include("sidebar.php"); ?>
  <?php include("header.php"); ?>

  <div class="main-content">
    <div class="profile-card">
      <div class="profile-image">
        <img src="images/profilepic.jpg" alt="Profile picture">
      </div>

      <div class="profile-info">
        <h2 class="username"><?= htmlspecialchars($user['username']); ?></h2>
        <p class="description"><?= htmlspecialchars($user['description']); ?></p>
      </div>

      <div class="streak-info">
        <div class="streak-flame">
          <span id="streak-number">8</span> 🔥
        </div>
        <p class="streak-label">Streak</p>
      </div>
    </div>

    <div class="profile-overview">
      <div class="overview-item">Current Steps: <span id="current-steps">0</span></div><br>
      <div class="overview-item">Next Workout: <span id="next-workout">No upcoming workout</span></div><br>
      <div class="overview-item" id="motivation">Keep pushing! 💪</div>
    </div>
  </div>

  <script src="script1.js"></script>
</body>
</html>
