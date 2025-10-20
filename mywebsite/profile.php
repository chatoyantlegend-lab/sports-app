<?php
session_start();
if(!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Username';
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

  <div class="main-content">
    <div class="top-right-icons">
      <div class="notif-container">
        <span id="notif-icon">🔔</span>
        <div class="notif-dropdown" id="notif-dropdown">
          <p><strong>Notifications:</strong></p><br>
          <a href="#">Friend request from Alex</a>
          <a href="#">New message from Jamie</a>
          <a href="#">Your post got 3 likes</a>
        </div>
      </div>

      <div class="settings-container">
        <span id="settings-icon">⚙️</span>
        <div class="settings-dropdown" id="settings-dropdown">
          <a href="edit-profile.php">Edit Profile</a>
          <a href="#">Account Settings</a>
          <a href="#">Privacy</a>
          <a href="logout.php" class="logout-btn">Logout</a>
        </div>
      </div>
    </div>

    <div class="profile-card">
      <div class="profile-image">
        <img src="images/profilepic.jpg" alt="Profile Picture">
      </div>

      <div class="profile-info">
        <h2 class="username" id="username-display"><?php echo htmlspecialchars($username); ?></h2>
        <p class="description" id="description-display">Tell us about yourself</p>
      </div>

      <div class="streak-info">
        <div class="streak-flame">
        <span id="streak-number">5</span>
        🔥</div>
        <p class="streak-label">Streak</p>
    </div>
    </div>
    <div class="profile-overview">
        <div class="overview-item">Current Steps: <span id="current-steps">0</span></div>
        <div class="overview-item">Next Workout: <span id="next-workout">No upcoming workout</span></div>
        <div class="overview-item" id="motivation">Keep pushing! 💪 </div>
        <div class="overview-item" id="motivation">Current Streak: <span id="streak-fire"> 🔥🔥 </span></div>
    
  </div>

  <script src="script1.js"></script>
  <script>
  document.addEventListener('DOMContentLoaded', () => {
    const editModal = document.getElementById('edit-profile-modal');
    const editBtn = document.getElementById('edit-profile-btn');
    const saveBtn = document.getElementById('save-profile');
    const cancelBtn = document.getElementById('cancel-edit');

    const savedUsername = localStorage.getItem('username');
    const savedDesc = localStorage.getItem('description');
    const savedPic = localStorage.getItem('profilePic');

    if (savedUsername) document.getElementById('username-display').textContent = savedUsername;
    if (savedDesc) document.getElementById('description-display').textContent = savedDesc;
    if (savedPic) document.querySelector('.profile-image img').src = savedPic;

    if (editBtn) {
      editBtn.addEventListener('click', () => {
        editModal.style.display = 'flex';
        document.getElementById('edit-username').value =
          document.getElementById('username-display').textContent;
        document.getElementById('edit-description').value =
          document.getElementById('description-display').textContent;
      });
    }

    if (saveBtn) {
      saveBtn.addEventListener('click', () => {
        const newUsername = document.getElementById('edit-username').value;
        const newDescription = document.getElementById('edit-description').value;

        document.getElementById('username-display').textContent = newUsername;
        document.getElementById('description-display').textContent = newDescription;

        localStorage.setItem('username', newUsername);
        localStorage.setItem('description', newDescription);

        editModal.style.display = 'none';
      });
    }

    if (cancelBtn) {
      cancelBtn.addEventListener('click', () => {
        editModal.style.display = 'none';
      });
    }
  });

  document.addEventListener("DOMContentLoaded", () => {
  const streakDays = 8; // replace with dynamic value later
  document.getElementById("streak-number").textContent = streakDays;
});

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
