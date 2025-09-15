<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Events – Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/Web-Based Platform for Office of Sports – AIUB/views/Admin/style.css">
</head>
<body>

<div class="sidebar">
   <img src="445492922_122100097214350632_1896056624552573141_n.jpg" alt="Admin" style="width:100px; border-radius:50%; margin:10px auto; display:block;">
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
    <div style="color:#0d6efd;">
        Welcome, <?= isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin' ?>
    </div>
</div>
<div class="main-content">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <input type="text" id="searchInput" class="form-control search-input" placeholder="Search events..." onkeyup="searchTable()">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">Add New Event</button>
  </div>

  <div class="card p-3">
    <div class="table-responsive">
      <table class="table table-hover align-middle" id="eventTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Event Name</th>
            <th>Date</th>
            <th>Venue</th>
            <th>Participants</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = $events->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= $row['date'] ?></td>
            <td><?= htmlspecialchars($row['venue']) ?></td>
            <td><?= $row['participants'] ?></td>
            <td>
              <span class="badge <?= $row['status']=='Scheduled'?'bg-success':($row['status']=='Pending'?'bg-secondary':'bg-primary') ?>">
                <?= $row['status'] ?>
              </span>
            </td>
            <td>
              <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editEventModal<?= $row['id'] ?>">Edit</button>
              <form method="post" style="display:inline;">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <input type="hidden" name="action" value="delete">
                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
              </form>
            </td>
          </tr>
          <div class="modal fade" id="editEventModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <form method="post">
                  <div class="modal-header">
                    <h5 class="modal-title">Edit Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="hidden" name="action" value="edit">
                    <div class="mb-3">
                      <label class="form-label">Event Name</label>
                      <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($row['name']) ?>" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Date</label>
                      <input type="date" class="form-control" name="date" value="<?= $row['date'] ?>" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Venue</label>
                      <input type="text" class="form-control" name="venue" value="<?= htmlspecialchars($row['venue']) ?>" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Participants</label>
                      <input type="number" class="form-control" name="participants" value="<?= $row['participants'] ?>" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Status</label>
                      <select class="form-select" name="status">
                        <option <?= $row['status']=='Active'?'selected':'' ?>>Active</option>
                        <option <?= $row['status']=='Pending'?'selected':'' ?>>Pending</option>
                        <option <?= $row['status']=='Completed'?'selected':'' ?>>Completed</option>
                      </select>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Update Event</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<div class="modal fade" id="addEventModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post">
        <div class="modal-header">
          <h5 class="modal-title">Add New Event</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="action" value="add">
          <div class="mb-3">
            <label class="form-label">Event Name</label>
            <input type="text" class="form-control" name="name" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" class="form-control" name="date" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Venue</label>
            <input type="text" class="form-control" name="venue" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Participants</label>
            <input type="number" class="form-control" name="participants" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="status">
              <option>Active</option>
              <option>Pending</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Event</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function searchTable() {
  const input = document.getElementById('searchInput').value.toLowerCase();
  const rows = document.querySelectorAll('#eventTable tbody tr');
  rows.forEach(row => {
    const name = row.cells[1].innerText.toLowerCase();
    const venue = row.cells[3].innerText.toLowerCase();
    row.style.display = (name.includes(input) || venue.includes(input)) ? '' : 'none';
  });
}
</script>
</body>
</html>
