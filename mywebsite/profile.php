
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
			<li><img src="images/profile.png" alt="Profile">Profile</li>
			<li><img src="images/activity.png" alt="Activity">Activity</li>
			<li><img src="images/friends.png" alt="Friends">Friends</li>
			<li><img src="images/schedule.png" alt="Schedule">Schedule</li>
		</ul>
	</div>
	

	<div class="main-content">
		<div class="top-right-icons">
			<span class="notifications">🔔</span>
			<span class="settings">⚙️</span>
			<span class="logout"><a href=logout.php>❌</a></span>
		</div>

		<div class="profile-card">

			<div class="profile-image">

			<img src="images/profilepic.jpg" alt="Picture">
			</div>

			<div class="profile-info">
				<h2 class="username">John Doe</h2>
				<p class="description"> Tell us about yourself</p>
			</div>
			
		</div>
	</div>
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


