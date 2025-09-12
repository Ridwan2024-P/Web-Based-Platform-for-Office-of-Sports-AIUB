<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upcoming Events – AIUB Sports</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/Web-Based Platform for Office of Sports – AIUB/views/Admin/style.css">
</head>
<body>
<div class="sidebar">
  <h4>User</h4>
  <a href="index.php?action=dashboardd">Dashboard</a>
  <a href="index.php?action=upcoming">Upcoming Events</a>
  <a href="index.php?action=myRegistrations">My Registrations</a>
  <a href="index.php?action=settings">Settings</a>
  <a href="index.php?action=logout">Logout</a>
</div>

<div class="top-navbar">
  <div><h3 style="color:#0d6efd;">Student</h3></div>
  <h6>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></h6>
</div>


<div class="container p-5">
  <h3>Upcoming Events</h3>
  <div class="row">
    <?php while($event = $events->fetch_assoc()): ?>
      <div class="col-md-4">
        <div class="card p-3 mb-3">
          <h5><?= htmlspecialchars($event['name']) ?></h5>
          <p>Date: <?= date("d M, Y", strtotime($event['date'])) ?></p>
          <p>Venue: <?= htmlspecialchars($event['venue']) ?></p>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>
</body>
</html>
