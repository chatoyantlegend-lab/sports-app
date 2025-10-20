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

    // Initialize FullCalendar
    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      selectable: true,
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: ''
      },

      // When a date is clicked
      dateClick: function(info) {
        modal.style.display = 'block';
        overlay.style.display = 'block';
        dateInput.value = info.dateStr;
      }
    });

    calendar.render();

    // Handle form submission
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const workout = document.getElementById('workout').value;
      const duration = document.getElementById('duration').value;
      const date = dateInput.value;

      // Add event to calendar
      calendar.addEvent({
        title: `${workout} (${duration} mins)`,
        start: date,
        allDay: true
      });

      // Hide modal
      modal.style.display = 'none';
      overlay.style.display = 'none';
      form.reset();
    });

    // Handle cancel button
    closeModal.addEventListener('click', function() {
      modal.style.display = 'none';
      overlay.style.display = 'none';
    });

    // Hide modal when clicking outside
    overlay.addEventListener('click', function() {
      modal.style.display = 'none';
      overlay.style.display = 'none';
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
