<?php 
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Activity</title>
  <link rel="stylesheet" href="css/style2.css">

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- Leaflet.js -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>
<body>

<?php include('sidebar.php'); ?>
<?php include('header.php'); ?>

<div class="activity-page">
  <h1 class="page-title">My Activity</h1>

  <!-- Tabs -->
  <div class="activity-tabs">
    <button class="activity-tab active" data-tab="challenges">Challenges</button>
    <button class="activity-tab" data-tab="activities">Activities (Map)</button>
    <button class="activity-tab" data-tab="progress">My Progress</button>
  </div>

  <!-- Tab 1 -->
  <div class="tab-content active" id="challenges">
    <h2>🏆 Challenges from Friends</h2>
    <p>Coming soon — friends will be able to challenge you to goals and activities!</p>
  </div>

  <!-- Tab 2 -->
  <div class="tab-content" id="activities">
    <h2>📍 Activities Near Eindhoven</h2>
    <div id="map"></div>
  </div>

  <!-- Tab 3 -->
  <div class="tab-content" id="progress">
    <h2>📊 My Daily Progress</h2>
    <div class="progress-section">
      <canvas id="progressChart" height="300"></canvas>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  // TAB SWITCHING
  const tabs = document.querySelectorAll('.activity-tab');
  const contents = document.querySelectorAll('.tab-content');

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => t.classList.remove('active'));
      contents.forEach(c => c.classList.remove('active'));
      tab.classList.add('active');
      document.getElementById(tab.dataset.tab).classList.add('active');

      // Fix for map or chart not showing when hidden before
      if (tab.dataset.tab === 'activities') {
        setTimeout(() => { map.invalidateSize(); }, 200);
      }
      if (tab.dataset.tab === 'progress') {
        progressChart.resize();
      }
    });
  });

  // MAP SETUP
  const map = L.map('map').setView([51.4416, 5.4697], 13);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  const gyms = [
    {name: "Basic-Fit Eindhoven", coords: [51.4405, 5.4775]},
    {name: "Sportcentrum Eindhoven", coords: [51.4453, 5.4551]},
    {name: "Gymtown Fitness", coords: [51.4439, 5.4665]},
  ];

  gyms.forEach(g => {
    L.marker(g.coords).addTo(map)
      .bindPopup(`<b>${g.name}</b><br>Fitness / Sports Center`);
  });

  // CHART
  const ctx = document.getElementById('progressChart');
  const progressChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
      datasets: [
        { 
          label: 'Steps', 
          data: [5000,7200,6800,9000,10000,8500,12000], 
          backgroundColor: '#2d3e50' 
        },
        { 
          label: 'Workout Minutes', 
          data: [30,45,20,60,50,40,70], 
          backgroundColor: '#4aa3ff' 
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'top' },
        title: { display: true, text: 'Weekly Progress' }
      },
      scales: { y: { beginAtZero: true } }
    }
  });

  // Highlight sidebar
  const currentPage = window.location.pathname.split("/").pop();
  document.querySelectorAll(".sidebar a").forEach(link => {
    if (link.getAttribute("href") === currentPage) link.classList.add("active");
  });
});
</script>
