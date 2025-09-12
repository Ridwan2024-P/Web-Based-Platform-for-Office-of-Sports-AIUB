<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard – AIUB Sports</title>
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

<div class="main-content">
  <?php if($message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <h4 class="mb-3">Upcoming Sports Events</h4>
  <div class="row">
    <?php while($event = $events->fetch_assoc()): ?>
      <div class="col-md-4">
        <div class="card p-3">
          <h5><?= htmlspecialchars($event['name']) ?></h5>
          <p>Date: <?= date("d M, Y", strtotime($event['date'])) ?></p>
          <p>Venue: <?= htmlspecialchars($event['venue']) ?></p>
          <form method="POST">
            <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
            <button class="btn btn-primary w-100" name="register_event">Register</button>

          </form>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

  <div class="card p-3 mt-5">
    <h4>My Participation History</h4>
    <div class="table-responsive">
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
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
