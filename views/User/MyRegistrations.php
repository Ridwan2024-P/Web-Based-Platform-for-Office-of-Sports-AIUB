<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Registrations – AIUB Sports</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/Web-Based Platform for Office of Sports – AIUB/views/Admin/style.css">
</head>
<body>


<div class="sidebar">
  <h4>User</h4>
  <a href="index.php?action=dashboardd">Dashboard</a>
  <a href="index.php?action=upcoming">Upcoming Events</a>
  <a href="index.php?action=myRegistrations">My Registrations</a>
  <a href="index.php?action=logout">Logout</a>
</div>


<div class="top-navbar">
  <div><h3 style="color:#0d6efd;">Student</h3></div>
  <h6>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></h6>
</div>

<div class="container p-5">
  <h3 >My Registrations</h3>
  <table class="table table-hover align-middle">
    <thead>
      <tr>
        <th>#</th>
        <th>Event</th>
        <th>Date</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php $i=1; while($row = $history->fetch_assoc()): ?>
      <tr>
        <td><?= $i++ ?></td>
        <td><?= htmlspecialchars($row['event_name']) ?></td>
        <td><?= htmlspecialchars($row['registration_date']) ?></td>
        <td><?= htmlspecialchars($row['status']) ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
<div>
</body>
</html>
