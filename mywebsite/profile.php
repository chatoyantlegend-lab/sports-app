
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
			<li id="btn-profile"><img src="images/profile.png" alt="Profile">Profile</li>
			<li id="btn-activity"><img src="images/activity.png" alt="Activity">Activity</li>
			<li id="btn-friends"><img src="images/friends.png" alt="Friends">Friends</li>
			<li id="btn-schedule"><img src="images/schedule.png" alt="Schedule">Schedule</li>
		</ul>
	</div>
	

	<div class="main-content">
		<div class="top-right-icons">
			<div class="notifications-container">
				<span class="notif" id="notif-icon">🔔</span>
				<div class="notif-dropdown" id="notif-dropdown">
				<p><strong>Notifications</p>
				<ul>
					<li> 💬 Alex sent you a message! </li>
					<li>❤️ Sarah liked your post</li>
					<li>👥 John added you as a friend</li>
				</ul>
				</div>
			</div>


			<div class="settings-container">
				<span class="setting" id="settings-icon">⚙️</span>
				<div class="settings-dropdown" id="settings-dropdown">
					<a href="edit-profile.php" class="edit-profile-link">Edit Profile</a>
					<a href="#" class="edit-profile-link">Account Settings</a>
					<a href="#" class="edit-profile-link">Privacy</a
					<a href="logout.php" class="edit-profile-link">Logout</a>
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


