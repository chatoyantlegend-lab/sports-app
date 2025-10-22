<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Schedule</title>

  <!-- FullCalendar CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.19/index.global.min.css" />

  <!-- Your site CSS -->
  <link rel="stylesheet" href="css/style2.css" />
</head>
<body>

  <?php include('sidebar.php'); ?>

  <div class="main-content">
    <h1 class="page-title">My Schedule</h1>

    <div id="calendar"></div>

    <!-- Overlay -->
    <div id="overlay"></div>

    <!-- Modal -->
    <div id="modal">
      <h2>Add Workout</h2>
      <form id="workoutForm">
        <label for="workout">Workout Type:</label>
        <input type="text" id="workout" name="workout" placeholder="e.g. Back workout" required>

        <label for="duration">Duration (minutes):</label>
        <input type="number" id="duration" name="duration" placeholder="e.g. 60" required>

        <input type="hidden" id="dateInput" name="date">

        <div style="display:flex; justify-content:space-between; margin-top:10px;">
          <button type="submit">Save</button>
          <button type="button" id="closeModal">Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <!-- FullCalendar JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.19/index.global.min.js"></script>

  <script>
document.addEventListener('DOMContentLoaded', function() {
  const calendarEl = document.getElementById('calendar');
  const modal = document.getElementById('modal');
  const overlay = document.getElementById('overlay');
  const form = document.getElementById('workoutForm');
  const dateInput = document.getElementById('dateInput');
  const closeModal = document.getElementById('closeModal');

  // Initialize calendar
  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    selectable: true,
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: ''
    },

    dateClick: function(info) {
      modal.style.display = 'block';
      overlay.style.display = 'block';
      dateInput.value = info.dateStr;
    }
  });

  

  // Load existing workouts
  fetch("load_workout.php")
    .then(response => response.json())
    .then(events => {
      events.forEach(e => calendar.addEvent(e));
      calendar.render(); // render after loading
    })
    .catch(err => {
      console.error("Error loading workouts:", err);
      calendar.render(); // still render calendar even if fetch fails
    });

  // Handle form submit
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    const workout = document.getElementById('workout').value;
    const duration = document.getElementById('duration').value;
    const date = dateInput.value;

    // Add to calendar immediately
    calendar.addEvent({
      title: workout + ' (' + duration + ' mins)',
      start: date,
      allDay: true
    });

    // Send to PHP backend (save to database)
    const formData = new FormData(form);
    fetch('save_workout.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.text())
    .then(text => console.log("Server:", text))
    .catch(err => console.error("Save error:", err));

    // Close modal
    modal.style.display = 'none';
    overlay.style.display = 'none';
    form.reset();
  });

  // Close modal handlers
  closeModal.addEventListener('click', function() {
    modal.style.display = 'none';
    overlay.style.display = 'none';
  });

  overlay.addEventListener('click', function() {
    modal.style.display = 'none';
    overlay.style.display = 'none';
  });
});


// Highlight sidebar active link
document.addEventListener("DOMContentLoaded", function() {
  const currentPage = window.location.pathname.split("/").pop();
  const links = document.querySelectorAll(".sidebar a");

  links.forEach(link => {
    if (link.getAttribute("href") === currentPage) {
      link.classList.add("active");
    }
  });
});



  
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
