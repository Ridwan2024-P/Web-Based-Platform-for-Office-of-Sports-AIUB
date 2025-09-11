<?php
session_start();
require_once 'models/Db.php'; // ডেটাবেস কানেকশন

// যদি লগইন না করে থাকে, redirect
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$conn = (new Db())->conn;

// Mark task as completed
if(isset($_POST['mark_completed'])){
    $task_id = $_POST['task_id'];
    $stmt = $conn->prepare("UPDATE volunteer_tasks SET status='Completed' WHERE id=? AND volunteer_id=?");
    $stmt->bind_param("ii", $task_id, $_SESSION['user_id']);
    $stmt->execute();
    $message = "Task marked as Completed!";
}

// Fetch assigned tasks
$stmt = $conn->prepare("SELECT * FROM volunteer_tasks WHERE volunteer_id=? ORDER BY task_date ASC");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$tasks = $stmt->get_result();

// Fetch announcements
$announcements_result = $conn->query("SELECT * FROM announcements ORDER BY event_date ASC");
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
.sidebar a { display: block; color: #fff; padding: 12px 20px; text-decoration: none; margin-bottom: 0.5rem; border-radius: 8px; transition: 0.2s;}
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
  <a href="#">Dashboard</a>
  <a href="#">Assigned Tasks</a>
  <a href="#">Update Progress</a>
  <a href="#">Profile</a>
  <a href="logout.php">Logout</a>
</div>

<div class="top-navbar">
  <h5>Volunteer Dashboard</h5>
  <div>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></div>
</div>

<div class="main-content">
<?php if(isset($message)): ?>
<div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<div class="card p-4">
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
<?php $i=1; while($row = $tasks->fetch_assoc()): 
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
<button class="btn btn-sm btn-success btn-complete" name="mark_completed" <?= $disabled ?>><?= $row['status']=='Completed' ? 'Completed' : 'Mark Completed' ?></button>
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
<?php while($row = $announcements_result->fetch_assoc()): ?>
<li class="list-group-item"><?= htmlspecialchars($row['message']) ?></li>
<?php endwhile; ?>
</ul>
</div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
