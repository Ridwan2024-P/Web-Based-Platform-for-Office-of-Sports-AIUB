<?php
// ======= Backend =======
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rid"; // change to your database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Approve / Reject
if (isset($_POST['action']) && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    if ($_POST['action'] === 'approve') {
        $conn->query("UPDATE registrations SET status='Approved' WHERE id=$id");
    } elseif ($_POST['action'] === 'reject') {
        $conn->query("UPDATE registrations SET status='Rejected' WHERE id=$id");
    }
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Fetch all registrations
$result = $conn->query("SELECT * FROM registrations ORDER BY registration_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Registrations – Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { display: flex; font-family: Arial, sans-serif; }
    .sidebar { width: 200px; background: #343a40; color: #fff; height: 100vh; padding: 15px; }
    .sidebar a { color: #fff; display: block; margin: 10px 0; text-decoration: none; }
    .sidebar h4 { margin-bottom: 20px; }
    .top-navbar { position: fixed; left: 200px; right: 0; top: 0; height: 60px; background: #f8f9fa; display: flex; justify-content: space-between; align-items: center; padding: 0 20px; border-bottom: 1px solid #ddd; }
    .main-content { margin-left: 200px; margin-top: 70px; padding: 20px; width: calc(100% - 200px); }
    .search-input { max-width: 300px; }
  </style>
</head>
<body>
  <div class="sidebar">
    <h4>Admin</h4>
    <a href="#">Dashboard</a>
    <a href="#">Manage Users</a>
    <a href="#">Manage Events</a>
    <a href="#">Registrations</a>
    <a href="#">Reports</a>
    <a href="#">Settings</a>
    <a href="#">Logout</a>
  </div>

  <div class="top-navbar">
    <h5>Manage Registrations</h5>
    <div>Welcome, Admin</div>
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
            <?php if ($result->num_rows > 0): ?>
              <?php while($row = $result->fetch_assoc()): ?>
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
                    <form method="POST" style="display:inline;">
                      <input type="hidden" name="id" value="<?= $row['id'] ?>">
                      <?php if ($status === 'Pending'): ?>
                        <button type="submit" name="action" value="approve" class="btn btn-sm btn-success btn-action">Approve</button>
                        <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger btn-action">Reject</button>
                      <?php elseif ($status === 'Approved'): ?>
                        <button class="btn btn-sm btn-secondary btn-action" disabled>Approved</button>
                        <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger btn-action">Reject</button>
                      <?php else: ?>
                        <button type="submit" name="action" value="approve" class="btn btn-sm btn-success btn-action">Approve</button>
                        <button class="btn btn-sm btn-secondary btn-action" disabled>Rejected</button>
                      <?php endif; ?>
                    </form>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
