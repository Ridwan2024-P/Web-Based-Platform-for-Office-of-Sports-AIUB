<?php
// $registrationsByEvent, $eventStatus, $recentRegistrations are passed from controller
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reports – Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
 <link rel="stylesheet" href="/Web-Based Platform for Office of Sports – AIUB/views/Admin/style.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="sidebar">
    <h4>Admin</h4>
    <a href="index.php?action=dashboard">Dashboard</a>
    <a href="index.php?action=manageUsers">Manage Users</a>
    <a href="index.php?action=manageEvents">Manage Events</a>
    <a href="index.php?action=manageRegistrations">Registrations</a>
    <a href="index.php?action=reports">Reports</a>
    <a href="/Web-Based Platform for Office of Sports – AIUB/views/Admin/Settings.html">Settings</a>
    <a href="index.php?action=logout">Logout</a>
  </div>
<div class="top-navbar">
    <h5>Reports</h5>
    <div>Welcome, Admin</div>
  </div>
<div class="main-content">
  

  <div class="row">
    <div class="col-md-6">
      <div class="card p-3 mb-3">
        <h5>Registrations by Event</h5>
        <canvas id="registrationsChart" class="chart-container"></canvas>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card p-3 mb-3">
        <h5>Event Status Distribution</h5>
        <canvas id="eventStatusChart" class="chart-container"></canvas>
      </div>
    </div>
  </div>

  <div class="card p-3">
    <h5>Recent Registrations</h5>
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Student Name</th>
            <th>Email</th>
            <th>Event</th>
            <th>Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($recentRegistrations as $r): ?>
          <tr>
            <td><?= $r['id'] ?></td>
            <td><?= htmlspecialchars($r['student_name']) ?></td>
            <td><?= htmlspecialchars($r['email']) ?></td>
            <td><?= htmlspecialchars($r['event_name']) ?></td>
            <td><?= $r['registration_date'] ?></td>
            <td>
              <span class="badge <?= $r['status']=='Approved'?'bg-success':($r['status']=='Pending'?'bg-warning':'bg-danger') ?>">
                <?= $r['status'] ?>
              </span>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Chart 1: Registrations by Event
const regLabels = <?= json_encode(array_column($registrationsByEvent,'event_name')) ?>;
const regData   = <?= json_encode(array_column($registrationsByEvent,'total')) ?>;
const ctx1 = document.getElementById('registrationsChart').getContext('2d');
new Chart(ctx1, {
    type: 'bar',
    data: { labels: regLabels, datasets: [{ label: 'Number of Registrations', data: regData, backgroundColor: '#0d6efd' }] },
    options: { responsive: true, plugins: { legend: { display: false } } }
});

// Chart 2: Event Status Distribution
const statusLabels = <?= json_encode(array_column($eventStatus,'status')) ?>;
const statusData   = <?= json_encode(array_column($eventStatus,'total')) ?>;
const ctx2 = document.getElementById('eventStatusChart').getContext('2d');
new Chart(ctx2, {
    type: 'pie',
    data: { labels: statusLabels, datasets: [{ label: 'Event Status', data: statusData, backgroundColor: ['#198754', '#ffc107', '#0d6efd'] }] },
    options: { responsive: true }
});
</script>
</body>
</html>
