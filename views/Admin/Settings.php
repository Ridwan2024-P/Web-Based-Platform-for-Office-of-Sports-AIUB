<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Settings – Admin Dashboard</title>
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
    <h5>Settings</h5>
    <div style="color:#0d6efd;">Welcome, <?= htmlspecialchars($_SESSION['username']) ?></div>
  </div>
  <div class="main-content container py-4">
    <?php if($success): ?>
      <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <div class="row g-4">
      <div class="col-md-6">
        <div class="card p-4">
          <h5>Update Profile</h5>
          <form action="index.php?action=updateProfile" method="POST">
            <div class="mb-3">
              <label for="name" class="form-label">Full Name</label>
              <input type="text" class="form-control" id="name" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="mb-3">
              <label for="role" class="form-label">Role</label>
              <input type="text" class="form-control" id="role" value="<?= htmlspecialchars($user['role']) ?>" disabled>
            </div>
            <button type="submit" class="btn btn-primary w-100">Update Profile</button>
          </form>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card p-4">
          <h5>Change Password</h5>
          <form action="index.php?action=changePassword" method="POST">
            <div class="mb-3">
              <label for="currentPassword" class="form-label">Current Password</label>
              <input type="password" class="form-control" id="currentPassword" name="current_password" required>
            </div>
            <div class="mb-3">
              <label for="newPassword" class="form-label">New Password</label>
              <input type="password" class="form-control" id="newPassword" name="new_password" required>
            </div>
            <div class="mb-3">
              <label for="confirmPassword" class="form-label">Confirm Password</label>
              <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-warning w-100">Change Password</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
