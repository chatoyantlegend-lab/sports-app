
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
		<ul>
			<li>Profile</li>
			<li>Activity</li>
			<li>Friends</li>
			<li>Schedule</li>
		</ul>
	</div>

	<div class="main-content">
		<div class="top-right-icons">
			<span class="notifications">🔔</span>
			<span class="settings">⚙️</span>
		</div>

		<div class="profile-cyllinder">
			<img src="your-profile-pic.jpg" alt="Profile Picture">
			<div class="username">username</div>
			<div class=-"description">Tell us about yourself..</div>
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

<h2>Welcome, <?php echo $_SESSION['user']; ?>!</h2>
<a href=logout.php>Logout</a>