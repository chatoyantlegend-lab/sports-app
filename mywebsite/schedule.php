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
        <input type="text" id="workout" name="workout" placeholder="e.g. Chest workout" required>

        <label for="startTime">Start Time:</label>
        <input type="time" id="startTime" name="start_time" required>

        <label for="endTime">End Time:</label>
        <input type="time" id="endTime" name="end_time" required>

        <label for="sets">Sets:</label>
        <input type="number" id="sets" name="sets" min="1" placeholder="e.g. 4">

        <label for="reps">Reps per Set:</label>
        <input type="number" id="reps" name="reps" min="1" placeholder="e.g. 12">

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
    initialView: 'timeGridWeek', // shows hours
    initialDate: new Date(),
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
    },
    eventClick: function(info) {
      const event = info.event;
      alert(
        `Workout: ${event.title}\nStart: ${event.start.toLocaleString()}\nEnd: ${event.end ? event.end.toLocaleString() : "N/A"}`
      );
    }
  });

  // Function to load workouts from the database
  function loadWorkouts() {
    fetch("load_workout.php")
      .then(response => response.json())
      .then(events => {
        console.log("Loaded events:", events);
        calendar.removeAllEvents();
        calendar.addEventSource(events);
        calendar.render();
      })
      .catch(err => {
        console.error("Error loading workouts:", err);
        calendar.render();
      });
  }

  // Load workouts on page load
  loadWorkouts();

  // Handle form submit (Save workout)
  form.addEventListener('submit', function(e) {
    e.preventDefault();

    const workout = document.getElementById('workout').value;
    const startTime = document.getElementById('startTime').value;
    const endTime = document.getElementById('endTime').value;
    const sets = document.getElementById('sets').value;
    const reps = document.getElementById('reps').value;
    const date = dateInput.value;

    const start = `${date}T${startTime}`;
    const end = `${date}T${endTime}`;

    // Add to calendar immediately (for instant feedback)
    calendar.addEvent({
      title: `${workout} (${sets}x${reps})`,
      start: start,
      end: end,
      allDay: false
    });

    // Send to backend (save to DB)
    const formData = new FormData(form);
    fetch('save_workout.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.text())
    .then(text => {
      console.log("Server:", text);
      // Reload events from database to stay consistent
      loadWorkouts();
    })
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
  </script>

</body>
</html>
