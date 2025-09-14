<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard – AIUB Sports</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/Web-Based Platform for Office of Sports – AIUB/views/Admin/style.css">
</head>
<body>
  <div class="sidebar">
    <h4>Admin</h4>
    <a href="index.php?action=dashboard">Dashboard</a>
    <a href="index.php?action=manageUsers">Manage Users</a>
    <a href="index.php?action=manageEvents">Manage Events</a>
    <a href="index.php?action=manageRegistrations">Registrations</a>
     <a href="index.php?action=adminTasks">Manage Tasks & Announcements</a>
    <a href="index.php?action=reports">Reports</a>
    <a href="index.php?action=settings">Settings</a>
    <a href="index.php?action=logout">Logout</a>
  </div>
  <div class="top-navbar">
    <h5>Dashboard</h5>
    <div  style="color:#0d6efd;">
        Welcome, <?= isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin' ?>
    </div>
</div>
  <div class="main-content">
    <div class="row g-4 mb-4">
      <div class="col-md-4">
        <div class="card p-3 text-center">
          <h5>Total Users</h5>
          <p class="display-6"><?= $totalUsers ?></p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-3 text-center">
          <h5>Total Volunteers</h5>
          <p class="display-6"><?= $totalVolunteers ?></p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-3 text-center">
          <h5>Active Events</h5>
          <p class="display-6"><?= $activeEvents ?></p>
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
              <th>Participants</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
          <?php while($row = $events->fetch_assoc()): ?>
            <tr>
              <td><?= $row['name'] ?></td>
              <td><?= $row['date'] ?></td>
              <td><?= $row['venue'] ?></td>
              <td><?= $row['participants'] ?></td>
              <td>
                <?php if($row['status'] == 'Scheduled'): ?>
                  <span class="badge bg-success">Scheduled</span>
                <?php else: ?>
                  <span class="badge bg-warning text-dark"><?= $row['status'] ?></span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
