
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard – AIUB Sports</title>
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
    <h5>Dashboard</h5>
    <div>Welcome, Admin</div>
  </div>
  <div class="main-content">
    <div class="row g-4 mb-4">
      <div class="col-md-3">
        <div class="card p-3 text-center">
          <h5>Total Users</h5>
          <p class="display-6">120</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card p-3 text-center">
          <h5>Active Events</h5>
          <p class="display-6">8</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card p-3 text-center">
          <h5>Pending Registrations</h5>
          <p class="display-6">25</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card p-3 text-center">
          <h5>Reports</h5>
          <p class="display-6">10</p>
        </div>
      </div>
    </div>
    <div class="card p-3 mb-4">
      <h5 class="mb-3">Upcoming Events</h5>
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Event Name</th>
              <th>Date</th>
              <th>Venue</th>
              <th>Registered Users</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Football Tournament</td>
              <td>2025-09-10</td>
              <td>Main Ground</td>
              <td>32</td>
              <td><span class="badge bg-success">Active</span></td>
            </tr>
            <tr>
              <td>Basketball League</td>
              <td>2025-09-15</td>
              <td>Sports Hall</td>
              <td>24</td>
              <td><span class="badge bg-warning text-dark">Pending</span></td>
            </tr>
            <tr>
              <td>Swimming Competition</td>
              <td>2025-09-20</td>
              <td>Swimming Pool</td>
              <td>18</td>
              <td><span class="badge bg-success">Active</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="row chart-container">
      <div class="col-md-6">
        <div class="card p-3">
          <h5 class="mb-3">User Registrations (Monthly)</h5>
          <canvas id="userChart"></canvas>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card p-3">
          <h5 class="mb-3">Event Participation</h5>
          <canvas id="eventChart"></canvas>
        </div>
      </div>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>

    const userCtx = document.getElementById('userChart').getContext('2d');
    new Chart(userCtx, {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
        datasets: [{
          label: 'Users',
          data: [10, 25, 18, 30, 22, 35, 28, 40],
          borderColor: '#0d6efd',
          backgroundColor: 'rgba(13,110,253,0.2)',
          tension: 0.3,
          fill: true
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          y: { beginAtZero: true }
        }
      }
    });
    const eventCtx = document.getElementById('eventChart').getContext('2d');
    new Chart(eventCtx, {
      type: 'bar',
      data: {
        labels: ['Football', 'Basketball', 'Swimming', 'Volleyball'],
        datasets: [{
          label: 'Participants',
          data: [32, 24, 18, 20],
          backgroundColor: ['#0d6efd','#ffc107','#198754','#dc3545']
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
      }
    });
  </script>

</body>
</html>
