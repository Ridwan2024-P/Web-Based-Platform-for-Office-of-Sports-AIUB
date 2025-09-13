<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Users – Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/Web-Based Platform for Office of Sports – AIUB/views/Admin/style.css">
</head>
<body>
<div class="sidebar">
    <h4><?= htmlspecialchars($_SESSION['role'] === 'admin' ? 'Admin' : 'Volunteer') ?></h4>

    <?php if($_SESSION['role'] === 'admin'): ?>
        <a href="index.php?action=dashboard">Dashboard</a>
        <a href="index.php?action=manageUsers">Manage Users</a>
        <a href="index.php?action=manageEvents">Manage Events</a>
        <a href="index.php?action=manageRegistrations">Registrations</a>
        <a href="index.php?action=adminTasks">Manage Tasks & Announcements</a>
        <a href="index.php?action=reports">Reports</a>
        <a href="index.php?action=settings">Settings</a>
    <?php elseif($_SESSION['role'] === 'volunteer'): ?>
       <a href="index.php?action=volunteerDashboard">Dashboard</a>
       <a href="index.php?action=manageUsers">Manage Users</a>
       <a href="index.php?action=manageRegistrations">Registrations</a>
      <a href="index.php?action=dashboardd">User Dashbaord</a>
    <?php endif; ?>

    <a href="index.php?action=logout">Logout</a>
</div>

  <div class="top-navbar">
    <h5>Dashboard</h5>
    <div>
        Welcome, <?= isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin' ?>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
<div class="d-flex justify-content-between align-items-center mb-3">
<input type="text" id="searchInput" class="form-control search-input" placeholder="Search users..." onkeyup="searchTable()">
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">Add New User</button>
</div>

<div class="card p-3">
<div class="table-responsive">
<table class="table table-hover align-middle" id="userTable">
<thead>
<tr>
<th>#</th>
<th>Name</th>
<th>Email</th>
<th>Role</th>
<th>Status</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php while($row = $users->fetch_assoc()): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['username'] ?></td>
<td><?= $row['email'] ?></td>
<td><?= $row['role'] ?></td>
<td><?= $row['status']==1?'<span class="badge bg-success">Active</span>':'<span class="badge bg-secondary">Inactive</span>' ?></td>
<td>
<button class="btn btn-sm btn-warning btn-action" data-bs-toggle="modal" data-bs-target="#editUserModal<?= $row['id'] ?>">Edit</button>
<a href="index.php?action=delete&delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger btn-action">Delete</a>
</td>
</tr>
<div class="modal fade" id="editUserModal<?= $row['id'] ?>" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">Edit User</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
<form method="POST" action="index.php?action=edit">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<div class="mb-3">
<label class="form-label">Name</label>
<input type="text" name="username" class="form-control" value="<?= $row['username'] ?>" required>
</div>
<div class="mb-3">
<label class="form-label">Email</label>
<input type="email" name="email" class="form-control" value="<?= $row['email'] ?>" required>
</div>
<div class="mb-3">
<label class="form-label">Password (leave blank to keep)</label>
<input type="password" name="password" class="form-control">
</div>
<div class="mb-3">
<label class="form-label">Role</label>
<?php if($_SESSION['role'] === 'admin'): ?>
    <select name="role" class="form-select">
        <option value="volunteer" <?= $row['role'] == 'volunteer' ? 'selected' : '' ?>>Volunteer</option>
        <option value="user" <?= $row['role'] == 'user' ? 'selected' : '' ?>>User</option>
    </select>
<?php else: ?>
    <select name="role" class="form-select" disabled>
        <option value="user" selected>User</option>
    </select>
<?php endif; ?>

</div>
<div class="mb-3">
<label class="form-label">Status</label>
<select name="status" class="form-select">
<option value="1" <?= $row['status']==1?'selected':'' ?>>Active</option>
<option value="0" <?= $row['status']==0?'selected':'' ?>>Inactive</option>
</select>
</div>
<button type="submit" name="editUser" class="btn btn-warning w-100">Update User</button>
</form>
</div>
</div>
</div>
</div>
<?php endwhile; ?>
</tbody>
</table>
</div>
</div>
</div>
<div class="modal fade" id="addUserModal" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">Add New User</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
<form method="POST" action="index.php?action=add">
<div class="mb-3">
<label class="form-label">Name</label>
<input type="text" name="username" class="form-control" required>
</div>
<div class="mb-3">
<label class="form-label">Email</label>
<input type="email" name="email" class="form-control" required>
</div>
<div class="mb-3">
<label class="form-label">Password</label>
<input type="password" name="password" class="form-control" required>
</div>
<div class="mb-3">
<label class="form-label">Role</label>
<select name="role" class="form-select">
<option>volunteer</option>
<option>user</option>
</select>
</div>
<div class="mb-3">
<label class="form-label">Status</label>
<select name="status" class="form-select">
<option value="1">Active</option>
<option value="0">Inactive</option>
</select>
</div>
<button type="submit" name="addUser" class="btn btn-primary w-100">Save User</button>
</form>
</div>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function searchTable() {
const input = document.getElementById('searchInput').value.toLowerCase();
const rows = document.querySelectorAll('#userTable tbody tr');
rows.forEach(row => {
const name = row.cells[1].innerText.toLowerCase();
const email = row.cells[2].innerText.toLowerCase();
row.style.display = (name.includes(input) || email.includes(input)) ? '' : 'none';
});
}
</script>
</body>
</html>
