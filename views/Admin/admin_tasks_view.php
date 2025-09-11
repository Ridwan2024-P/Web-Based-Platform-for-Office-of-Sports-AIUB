<?php if(!isset($message)) $message=''; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin – Manage Tasks</title>
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
<h5>Manage Tasks & Announcements</h5>
<div>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></div>
</div>

<div class="main-content">
<?php if($message): ?>
<div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<div class="row g-4">

<!-- Add Task -->
<div class="col-md-6">
<div class="card p-4">
<h5>Add New Task</h5>
<form method="POST">
<div class="mb-3">
<label class="form-label">Volunteer</label>
<select name="volunteer_id" class="form-select" required>
<option value="">Select Volunteer</option>
<?php while($v = $volunteers->fetch_assoc()): ?>
<option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['username']) ?></option>
<?php endwhile; ?>
</select>
</div>
<div class="mb-3">
<label class="form-label">Event Name</label>
<select name="event_id" class="form-select" required>
<option value="">Select Event</option>
<?php while($e = $events->fetch_assoc()): ?>
<option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['name']) ?></option>
<?php endwhile; ?>
</select>
</div>
<div class="mb-3">
<label class="form-label">Task Name</label>
<input type="text" name="task_name" class="form-control" required>
</div>
<div class="mb-3">
<label class="form-label">Task Date</label>
<input type="date" name="task_date" class="form-control" required>
</div>
<button type="submit" name="add_task" class="btn btn-primary w-100">Add Task</button>
</form>
</div>
</div>

<!-- Add Announcement -->
<div class="col-md-6">
<div class="card p-4">
<h5>Add Announcement</h5>
<form method="POST">
<div class="mb-3">
<label class="form-label">Message</label>
<textarea name="add_announcement" class="form-control" rows="3" required></textarea>
</div>
<div class="mb-3">
<label class="form-label">Event Date (Optional)</label>
<input type="date" name="event_date" class="form-control">
</div>
<button type="submit" name="add_announcement" class="btn btn-success w-100">Add Announcement</button>
</form>
</div>
</div>

</div>
<h5 class="mt-5">Existing Tasks</h5>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Volunteer</th>
            <th>Event</th>
            <th>Task</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php while($task = $tasks->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($task['volunteer_name']) ?></td>
            <td><?= htmlspecialchars($task['event_name']) ?></td>
            <td><?= htmlspecialchars($task['task_name']) ?></td>
            <td><?= htmlspecialchars($task['task_date']) ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
