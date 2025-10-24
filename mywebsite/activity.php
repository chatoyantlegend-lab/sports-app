<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Activity</title>

  <!-- CSS -->
  <link rel="stylesheet" href="css/style2.css" />

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Leaflet.js for Map -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <style>
    body {
      background-color: #f5f5f5;
      color: #333;
      font-family: Arial, sans-serif;
    }

    .main-content {
      margin-left: 260px;
      padding: 40px 60px;
    }
    .page-title {
      margin-left: 0
    }

    .activity-tabs {
      display: flex;
      gap: 15px;
      margin-top: 20px;
    }

    .activity-tab {
      padding: 10px 20px;
      background-color: #2d3e50;
      color: white;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .activity-tab:hover {
      background-color: #3e5065;
    }

    .activity-tab.active {
      background-color: #4aa3ff;
      font-weight: bold;
    }

    .tab-content {
      display: none;
      margin-top: 30px;
      width: 75%;
      height: 80%;
    }

    .tab-content.active {
      display: block;
    }

    #map {
      height: 400px;
      width: 85%;
      border-radius: 10px;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
    }

    .challenge-item {
      background: #fff;
      padding: 15px;
      margin-bottom: 15px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .challenge-item button {
      background-color: #4aa3ff;
      color: white;
      border: none;
      border-radius: 6px;
      padding: 6px 12px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .challenge-item button:hover {
      background-color: #2d3e50;
    }
  </style>
</head>

<body>
  <?php include('sidebar.php'); ?>

  <div class="main-content">
    <h1 class="page-title">My Activity</h1>

    <!-- Tabs -->
    <div class="activity-tabs">
      <div class="activity-tab active" data-tab="activities">Activities</div>
      <div class="activity-tab" data-tab="progress">My Progress</div>
      <div class="activity-tab" data-tab="challenges">Challenges</div>
    </div>

    <!-- TAB 1: Activities -->
    <div class="tab-content active" id="activities">
      <h2>Fitness Locations in Eindhoven</h2>
      <p>Explore nearby gyms and fitness centers around Eindhoven!</p>
      <div id="map"></div>
    </div>

    <!-- TAB 2: Progress -->
    <div class="tab-content" id="progress">
      <h2>My Weekly Progress</h2>
      <p>Your personal activity overview (calories, steps, workout time)</p>
      <canvas id="progressChart" width="600" height="400"></canvas>
    </div>

    <!-- TAB 3: Challenges -->
    <div class="tab-content" id="challenges">
      <h2>Community Challenges</h2>
      <div class="challenge-item">
        🏃 5km Park Run Challenge 
        <button onclick="joinChallenge(this)">Join</button>
      </div>
      <div class="challenge-item">
        💪 50 Push-Ups a Day 
        <button onclick="joinChallenge(this)">Join</button>
      </div>
      <div class="challenge-item">
        🚴 100km Cycling Week 
        <button onclick="joinChallenge(this)">Join</button>
      </div>
    </div>
  </div>

  <!-- JAVASCRIPT -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // --- Tab switching logic ---
      const tabs = document.querySelectorAll(".activity-tab");
      const contents = document.querySelectorAll(".tab-content");

      tabs.forEach(tab => {
        tab.addEventListener("click", () => {
          tabs.forEach(t => t.classList.remove("active"));
          contents.forEach(c => c.classList.remove("active"));
          tab.classList.add("active");
          document.getElementById(tab.dataset.tab).classList.add("active");
        });
      });

      // --- Leaflet Map Setup ---
      const map = L.map("map").setView([51.4416, 5.4697], 13);
      L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: '&copy; OpenStreetMap contributors'
      }).addTo(map);

      const gyms = [
        { name: "Basic-Fit Eindhoven Centrum", coords: [51.4406, 5.4772], address: "Stationsplein 10, Eindhoven" },
        { name: "Fit For Free Eindhoven", coords: [51.4431, 5.4568], address: "Edisonstraat 2, Eindhoven" },
        { name: "SportCity Eindhoven", coords: [51.4399, 5.4800], address: "Vestdijk 31, Eindhoven" },
        { name: "TrainMore Eindhoven", coords: [51.4388, 5.4723], address: "Kleine Berg 14, Eindhoven" }
      ];

      gyms.forEach(gym => {
        L.marker(gym.coords)
          .addTo(map)
          .bindPopup(`<b>${gym.name}</b><br>${gym.address}`);
      });

      // --- Chart.js Progress Chart ---
      const ctx = document.getElementById("progressChart").getContext("2d");
      new Chart(ctx, {
        type: "bar",
        data: {
          labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
          datasets: [
            {
              label: "Calories Burned",
              data: [400, 500, 300, 600, 700, 800, 650],
              backgroundColor: "#4aa3ff"
            },
            {
              label: "Steps",
              data: [5000, 8000, 7000, 6000, 9000, 11000, 12000],
              backgroundColor: "#2d3e50"
            },
            {
              label: "Workout Time (hrs)",
              data: [1, 1.5, 0.5, 1, 2, 2.5, 2],
              backgroundColor: "#ffb84d"
            }
          ]
        },
        options: {
          responsive: true,
          animation: {
            duration: 1200,
            easing: "easeOutQuart"
          },
          scales: {
            y: {
              beginAtZero: true
            }
          },
          plugins: {
            legend: {
              position: "bottom"
            }
          }
        }
      });
    });

    // --- Challenge join button ---
    function joinChallenge(button) {
      button.textContent = "Joined ✅";
      button.disabled = true;
      button.style.backgroundColor = "gray";
    }

    // --- Highlight active sidebar link ---
    document.addEventListener("DOMContentLoaded", function() {
      const currentPage = window.location.pathname.split("/").pop();
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
