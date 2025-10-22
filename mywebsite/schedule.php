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
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Schedule</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.19/index.global.min.css">
  <link rel="stylesheet" href="css/style2.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.19/index.global.min.js"></script>
</head>
<body>

  <?php include("sidebar.php"); ?>
  <?php include("header.php"); ?>

  <div class="main-content">
    <h1 class="page-title">My Schedule</h1>
    <div id="calendar"></div>

    <div id="overlay"></div>

    <div id="modal">
      <h2>Add Workout</h2>
      <form id="workoutForm">
        <label>Workout Type:</label>
        <input type="text" id="workout" name="workout" placeholder="e.g. Back workout" required>

        <label>Duration (minutes):</label>
        <input type="number" id="duration" name="duration" placeholder="e.g. 60" required>

        <input type="hidden" id="dateInput" name="date">

        <div style="display:flex; justify-content:space-between; margin-top:10px;">
          <button type="submit">Save</button>
          <button type="button" id="closeModal">Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const modal = document.getElementById('modal');
    const overlay = document.getElementById('overlay');
    const form = document.getElementById('workoutForm');
    const dateInput = document.getElementById('dateInput');
    const closeModal = document.getElementById('closeModal');

    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      selectable: true,
      headerToolbar: { left: 'prev,next today', center: 'title', right: '' },
      dateClick(info) {
        modal.style.display = 'block';
        overlay.style.display = 'block';
        dateInput.value = info.dateStr;
      }
    });

    fetch("load_workout.php")
      .then(r => r.json())
      .then(events => { events.forEach(e => calendar.addEvent(e)); calendar.render(); })
      .catch(() => calendar.render());

    form.addEventListener('submit', e => {
      e.preventDefault();
      const formData = new FormData(form);
      fetch('save_workout.php', { method: 'POST', body: formData });
      calendar.addEvent({ title: `${formData.get('workout')} (${formData.get('duration')} mins)`, start: formData.get('date'), allDay: true });
      modal.style.display = 'none'; overlay.style.display = 'none'; form.reset();
    });

    closeModal.addEventListener('click', () => { modal.style.display = 'none'; overlay.style.display = 'none'; });
    overlay.addEventListener('click', () => { modal.style.display = 'none'; overlay.style.display = 'none'; });
  });
  </script>
</body>
</html>
