<?php
// Start session only if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Make sure database connection exists
if (!isset($conn)) {
    include("includes/db.php");
}
?>

<div class="top-right-icons">

  <!-- 🔔 Notification Bell -->
  <div class="notif-container">
    <img src="images/bell.png" alt="Notifications" class="notif-bell" id="notif-bell">
    <span id="notif-count" class="notif-badge">0</span>
    <div class="notif-dropdown" id="notif-dropdown">
      <h4>Notifications</h4>
      <div id="notifications-list"><p>Loading...</p></div>
    </div>
  </div>

  <!-- ⚙️ Settings Dropdown -->
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

<script>
document.addEventListener("DOMContentLoaded", () => {
  const bell = document.getElementById("notif-bell");
  const dropdown = document.getElementById("notif-dropdown");
  const notifCount = document.getElementById("notif-count");
  const notifList = document.getElementById("notifications-list");

  // Toggle dropdown visibility
  bell.addEventListener("click", () => {
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
  });

  // Fetch notifications from the API
  async function loadNotifications() {
    const res = await fetch("api/get_notifications.php");
    const data = await res.json();

    if (!data.success) return;

    notifCount.style.display = data.count > 0 ? "block" : "none";
    notifCount.textContent = data.count;

    notifList.innerHTML = "";
    if (data.count === 0) {
      notifList.innerHTML = "<p>No new notifications</p>";
    } else {
      data.requests.forEach(req => {
        const item = document.createElement("div");
        item.classList.add("notif-item");
        item.innerHTML = `
          <p><strong>${req.username}</strong> sent you a friend request</p>
          <button class="accept-btn" data-id="${req.id}">Accept</button>
          <button class="reject-btn" data-id="${req.id}">Reject</button>
        `;
        notifList.appendChild(item);
      });

      // Handle friend actions
      notifList.querySelectorAll(".accept-btn, .reject-btn").forEach(btn => {
        btn.addEventListener("click", async () => {
          const action = btn.classList.contains("accept-btn") ? "accept" : "reject";
          const response = await fetch("api/respond_friend.php", {
            method: "POST",
            body: new URLSearchParams({ request_id: btn.dataset.id, action })
          });
          const result = await response.json();
          alert(result.message);
          loadNotifications(); // Refresh after action
        });
      });
    }
  }

  // Load notifications on page load + refresh every 10 seconds
  loadNotifications();
  setInterval(loadNotifications, 10000);

  // Toggle settings dropdown
  const settingsIcon = document.getElementById("settings-icon");
  const settingsDropdown = document.getElementById("settings-dropdown");

  settingsIcon.addEventListener("click", () => {
    settingsDropdown.style.display = settingsDropdown.style.display === "block" ? "none" : "block";
  });
});
</script>
