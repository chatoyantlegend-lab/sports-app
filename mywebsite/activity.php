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
    <meta name="viewport" contents="width-device-width, initial-scale=1.0">
    <title>Activity</title>
    <link rel="stylesheet" href="css/style2.css">

    <!-- Chart.js for progress graphs -->
    <script src="https://cdn.delivrnet/npm/chart.js"></script>
    <style>
        .activity-tabs {
            display: flex;
            gap: 15px;
            margin-left: 260px;
            margin-top: 30px
        }

        .activity-tab {
            padding: 10px 20px;
            background-color: #2d3e50;
            color: white;
            border-radius: 8px;
            cursor: pointer;
        }

        .activity-tab.active {
            background-color: #3e5065;
            font-weight: bold;
        }

        .tab-content {
            margin-left: 260px;
            margin-top: 20px;
            display: none;

        }

        .tab-content.active {
            display: block;
        }

        .challenge-item, .streak-item {
            background: #fff;
            padding: 12px;
            margin-bottom:10px;
            border-radius:8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

    </style>
</head>
<body>

<?php include('sidebar.php'); ?>

<div class="main-content">
    <h1 class="page-title">My Activity</h1>

    <!-- Tabs -->
     <div class="activity-tabs">
        <div class="activity-tab active" data-tab="challenges">Challenges</div>
        <div class="activity-tab" data-tab="progress">My Progress</div>
        <div class="activity-tab" data-tab="streaks">Streaks</div>
    </div>

    <!-- Tab Contents -->
     <div class="tab-content active" id="challenges">
        <h2>Challenges & Events in Eindhoven </h2>
        <div class="challenge-item">Challenge : 10k Steps in 1 Day</div>
        <div class="challenge-item">Event: Sunday Run 5km</div>
        <div class="challenge-item">Challenge: Push-up Contest</div>
    </div>

    <div class="tab-content" id="progress">
        <h2>My Weekly Progress</h2>
        <canvas id="progressChart" width="600" height="400"></canvas>
    </div>

    <div class="tab-content" id="streaks">
        <h2>Friends' Streaks</h2>
        <div class="streak-item">Alex: 🔥🔥🔥🔥 4-day streak</div>
        <div class="streak-item">Jamie: 🔥🔥 2-day streak</div>
        <div class="streak-item">Sophie: 🔥🔥🔥 3-day streak</div>
    </div>
</div>

<script>
  // Tab switching logic
  const tabs = document.querySelectorAll('.activity-tab');
  const contents = document.querySelectorAll('.tab-content');

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      // remove active class
      tabs.forEach(t => t.classList.remove('active'));
      contents.forEach(c => c.classList.remove('active'));

      // add active class to clicked tab
      tab.classList.add('active');
      document.getElementById(tab.dataset.tab).classList.add('active');
    });
  });

  // Example Chart.js graph for My Progress
  const ctx = document.getElementById('progressChart').getContext('2d');
  const progressChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
      datasets: [
        { label: 'Steps', data: [5000, 7000, 8000, 6000, 9000, 7500, 10000], backgroundColor: '#2d3e50' },
        { label: 'Workout Hours', data: [1,1.5,0.5,1,2,1,2], backgroundColor: '#4aa3ff' }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'top' }
      },
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
});

</script>
<script>
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