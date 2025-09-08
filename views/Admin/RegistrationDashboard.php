<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Registrations – Admin Dashboard</title>
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
    <a href="index.php?action=reports">Reports</a>
    <a href="index.php?action=settings">Settings</a>
    <a href="index.php?action=logout">Logout</a>
  </div>
    <div class="top-navbar">
    <h5>Dashboard</h5>
    <div>
        Welcome, <?= isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin' ?>
    </div>
</div>
  <div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <input type="text" id="searchInput" class="form-control search-input" placeholder="Search registration..." onkeyup="searchTable()">
    </div>

    <div class="card p-3">
      <div class="table-responsive">
        <table class="table table-hover align-middle" id="registrationTable">
          <thead>
            <tr>
              <th>#</th>
              <th>Student Name</th>
              <th>Email</th>
              <th>Event</th>
              <th>Registration Date</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($registrations && $registrations->num_rows > 0): ?>
              <?php while($row = $registrations->fetch_assoc()): ?>
                <tr>
                  <td><?= $row['id'] ?></td>
                  <td><?= htmlspecialchars($row['student_name']) ?></td>
                  <td><?= htmlspecialchars($row['email']) ?></td>
                  <td><?= htmlspecialchars($row['event_name']) ?></td>
                  <td><?= $row['registration_date'] ?></td>
                  <td>
                    <?php
                      $status = $row['status'];
                      $badgeClass = match($status) {
                          'Pending' => 'bg-warning',
                          'Approved' => 'bg-success',
                          'Rejected' => 'bg-danger'
                      };
                    ?>
                    <span class="badge <?= $badgeClass ?>"><?= $status ?></span>
                  </td>
                  <td>
                    <form method="POST" action="?action=registrationAction" style="display:inline;">
                      <input type="hidden" name="id" value="<?= $row['id'] ?>">
                      <?php if ($status === 'Pending'): ?>
                        <button type="submit" name="action" value="approve" class="btn btn-sm btn-success">Approve</button>
                        <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger">Reject</button>
                      <?php elseif ($status === 'Approved'): ?>
                        <button class="btn btn-sm btn-secondary" disabled>Approved</button>
                        <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger">Reject</button>
                      <?php else: ?>
                        <button type="submit" name="action" value="approve" class="btn btn-sm btn-success">Approve</button>
                        <button class="btn btn-sm btn-secondary" disabled>Rejected</button>
                      <?php endif; ?>
                    </form>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="7" class="text-center">No registrations found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    function searchTable() {
      const input = document.getElementById('searchInput').value.toLowerCase();
      const rows = document.querySelectorAll('#registrationTable tbody tr');
      rows.forEach(row => {
        const name = row.cells[1].innerText.toLowerCase();
        const email = row.cells[2].innerText.toLowerCase();
        const event = row.cells[3].innerText.toLowerCase();
        row.style.display = (name.includes(input) || email.includes(input) || event.includes(input)) ? '' : 'none';
      });
    }
  </script>
</body>
</html>
