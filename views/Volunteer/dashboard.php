<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Progress – Volunteer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Web-Based Platform for Office of Sports – AIUB/views/Admin/style.css">
</head>
<body>
    <div class="sidebar">
  <h4>Volunteer</h4>
  
    <a href="index.php?action=volunteerDashboard">Dashboard</a>
      <a href="index.php?action=manageUsers">Manage Users</a>
      <a href="index.php?action=manageRegistrations">Registrations</a>
      <a href="index.php?action=dashboardd">User Dashboard</a>
      <a href="index.php?action=logout">Logout</a>
</div>

    <div class="top-navbar">
        <h5>Update Progress</h5>
        <div>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></div>
    </div>

    <div class="main-content">
        <?php if($message): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="card p-4">
            <h4>Assigned Tasks – Update Progress</h4>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Event</th>
                            <th>Task</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Update Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; while($row = $tasks->fetch_assoc()): ?>
                            <?php
                            $status_class = $row['status']=='Completed' ? 'bg-success' : ($row['status']=='In Progress' ? 'bg-secondary' : 'bg-warning');
                            $disabled = $row['status']=='Completed' ? 'disabled' : '';
                            ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($row['event_name']) ?></td>
                                <td><?= htmlspecialchars($row['task_name']) ?></td>
                                <td><?= htmlspecialchars($row['task_date']) ?></td>
                                <td><span class="badge <?= $status_class ?> badge-status"><?= htmlspecialchars($row['status']) ?></span></td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="task_id" value="<?= $row['id'] ?>">
                                        <select name="status" class="form-select form-select-sm" <?= $disabled ?>>
                                            <option value="Pending" <?= $row['status']=='Pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="In Progress" <?= $row['status']=='In Progress' ? 'selected' : '' ?>>In Progress</option>
                                            <option value="Completed" <?= $row['status']=='Completed' ? 'selected' : '' ?>>Completed</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn btn-sm btn-primary mt-1" <?= $disabled ?>>Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card p-4">
            <h4>Event Announcements</h4>
            <ul class="list-group">
                <?php while($row = $announcements->fetch_assoc()): ?>
                    <li class="list-group-item"><?= htmlspecialchars($row['message']) ?></li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
