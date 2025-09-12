<?php
// $tasks, $announcements, $message passed from controller
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Volunteer Dashboard – AIUB Sports</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family: 'Poppins', sans-serif; background: #f4f6f9; }
.sidebar { height: 100vh; width: 220px; position: fixed; top: 0; left: 0; background-color: #0d6efd; padding-top: 2rem; color: #fff; }
.sidebar a { display: block; color: #fff; padding: 12px 20px; text-decoration: none; margin-bottom: 0.5rem; border-radius: 8px; transition: 0.2s; }
.sidebar a:hover { background-color: #084298; }
.sidebar h4 { text-align: center; margin-bottom: 2rem; color: #ffd369; }
.top-navbar { background-color: #fff; padding: 1rem 2rem; margin-left: 220px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 1000; }
.top-navbar h5 { margin: 0; color: #0d6efd; }
.main-content { margin-left: 220px; padding: 2rem; }
.card { border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 2rem; transition: transform 0.2s, box-shadow 0.2s; }
.card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
.card h4 { font-weight: 600; color: #0d6efd; margin-bottom: 1rem; }
.badge-status { font-size: 0.9rem; }
.btn-complete { transition: 0.3s; }
.btn-complete:hover { transform: scale(1.05); }
</style>
</head>
<body>

<div class="sidebar">
  <h4>Volunteer</h4>
  <a href="?action=dashboard">Dashboard</a>
  <a href="?action=dashboard#tasks">Assigned Tasks</a>
  <a href="?action=dashboard#progress">Update Progress</a>
  <a href="#">Profile</a>
  <a href="#">Logout</a>
</div>

<div class="top-navbar">
  <h5>Volunteer Dashboard</h5>
  <div>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></div>
</div>

<div class="main-content">

<?php if(!empty($message)): ?>
<div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<!-- DASHBOARD / OVERVIEW -->
<div class="card p-4" id="dashboard">
<h4>Overview</h4>
<p>Total Assigned Tasks: <?= $tasks->num_rows ?></p>
<p>Total Announcements: <?= $announcements->num_rows ?></p>
</div>

<!-- ASSIGNED TASKS -->
<div class="card p-4" id="tasks">
<h4>Assigned Tasks</h4>
<div class="table-responsive">
<table class="table table-hover align-middle">
<thead>
<tr>
<th>#</th>
<th>Event</th>
<th>Task</th>
<th>Date</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php $i=1; $tasks->data_seek(0); while($row = $tasks->fetch_assoc()): 
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
<form method="POST" action="?action=dashboard#progress">
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

<!-- EVENT ANNOUNCEMENTS -->
<div class="card p-4" id="progress">
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
