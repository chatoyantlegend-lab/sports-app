<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current user info
$query = "SELECT username, description, profile_pic FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = mysqli_real_escape_string($conn, $_POST['username']);
    $new_description = mysqli_real_escape_string($conn, $_POST['description']);

    // Default to current profile picture
    $profile_pic_path = $user['profile_pic'] ?? 'uploads/default.jpg';

    // Check if a new picture is uploaded
    if (isset($_FILES['profile-pic']) && $_FILES['profile-pic']['error'] === 0) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        $file_ext = pathinfo($_FILES['profile-pic']['name'], PATHINFO_EXTENSION);
        $file_name = $user_id . "_" . time() . "." . $file_ext;
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['profile-pic']['tmp_name'], $target_file)) {
            $profile_pic_path = $target_file;
        }
    }

    // Update database
    $stmt = $conn->prepare("UPDATE users SET username=?, description=?, profile_pic=? WHERE id=?");
    $stmt->bind_param("sssi", $new_username, $new_description, $profile_pic_path, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: profile.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width-device-width, initial-scale=1.0">
	<title>Edit Profile</title>
	<link rel="stylesheet" href="css/style2.css">
</head>
	
<body>

<form method="POST" action="" enctype="multipart/form-data">
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

<script>
document.getElementById('profile-pic').addEventListener('change', function(event){
    const preview = document.getElementById('preview');
    const file = event.target.files[0];
    if(file){
        preview.src = URL.createObjectURL(file);
    }
});
</script>

<!-- <script src="edit-profile.js">-->
	</script>
	</body>
	</html>
