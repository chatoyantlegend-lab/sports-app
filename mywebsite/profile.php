
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Profile</title>
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
	

	<div class="main-content">
		<div class="top-right-icons">
			<div class="notif-container">
    <span id="notif-icon">🔔</span>
    <div class="notif-dropdown" id="notif-dropdown">
	<p><strong>Notifications:</strong></p><br></br>
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
				<h2 class="username" id="username-display">Username</h2>
				<p class="description" id="description-display"> Tell us about yourself</p>
			</div>
		</div>

		
    </div>
  </div>
</div>
<script src="script1.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const editModal = document.getElementById('edit-profile-modal');
  const editBtn = document.getElementById('edit-profile-btn');
  const saveBtn = document.getElementById('save-profile');
  const cancelBtn = document.getElementById('cancel-edit');

  // Load saved data
  const savedUsername = localStorage.getItem('username');
  const savedDesc = localStorage.getItem('description');
  const savedPic = localStorage.getItem('profilePic');

  if (savedUsername) document.getElementById('username-display').textContent = savedUsername;
  if (savedDesc) document.getElementById('description-display').textContent = savedDesc;
  if (savedPic) document.querySelector('.profile-image img').src = savedPic;

  // Open modal
  if (editBtn) {
    editBtn.addEventListener('click', () => {
      editModal.style.display = 'flex';
      document.getElementById('edit-username').value =
        document.getElementById('username-display').textContent;
      document.getElementById('edit-description').value =
        document.getElementById('description-display').textContent;
    });
  }

  // Save profile
  if (saveBtn) {
    saveBtn.addEventListener('click', () => {
      const newUsername = document.getElementById('edit-username').value;
      const newDescription = document.getElementById('edit-description').value;

      // Update UI
      document.getElementById('username-display').textContent = newUsername;
      document.getElementById('description-display').textContent = newDescription;

      // Save to localStorage
      localStorage.setItem('username', newUsername);
      localStorage.setItem('description', newDescription);

      // Close modal
      editModal.style.display = 'none';
    });
  }

  // Cancel edit
  if (cancelBtn) {
    cancelBtn.addEventListener('click', () => {
      editModal.style.display = 'none';
    });
  }
});
</script>



</body>
</html>
<?php

session_start();
if(!isset($_SESSION['user']))
	{
		header("Location: login.php");
		exit();
	}

?>


