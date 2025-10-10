<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width-device-width, initial-scale=1.0">
	<title>Edit Profile</title>
	<link rel="stylesheet" href="css/style2.css">
</head>
	
<body>
	<div class="edit-profile-container">
  <h2>Edit Profile</h2>

  <label for="profile-pic">Profile Picture</label>
  <input type="file" id="profile-pic" name="profile-pic" accept="image/*">
  <img id="preview" class="profile-pic-preview" src="uploads/default.png" alt="Profile Preview">

  <label for="username">Username</label>
  <input type="text" id="username" name="username" value="CurrentUserName">

  <label for="description">Description</label>
  <textarea id="description" name="description" rows="4">Your current description...</textarea>

  <div class="button-group">
    <button type="submit" id="save-profile">Save</button>
    <button type="button" onclick="window.location.href='profile.php'">Cancel</button>
  </div>
</div>


	<script src="edit-profile.js">
	</script>
	</body>
	</html>
