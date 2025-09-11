<?php
session_start();
require_once 'models/Db.php'; 

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$conn = (new Db())->conn;
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT username, email, role, password FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$success = '';
$error = '';
if(isset($_POST['update_profile'])){
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("UPDATE users SET username=?, email=? WHERE id=?");
    $stmt->bind_param("ssi", $username, $email, $user_id);

    if($stmt->execute()){
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $success = "Profile updated successfully";
        $user['username'] = $username;
        $user['email'] = $email;
    } else {
        $error = "Failed to update profile";
    }
}
if(isset($_POST['change_password'])){
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if(!password_verify($current, $user['password'])){
        $error = "Current password is incorrect";
    } elseif($new !== $confirm){
        $error = "New password and confirm password do not match";
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt->bind_param("si", $hash, $user_id);
        if($stmt->execute()){
            $success = "Password changed successfully";
        } else {
            $error = "Failed to change password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Settings – Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{font-family:Poppins,sans-serif;background:#f4f6f9;}
.sidebar{height:100vh;width:220px;position:fixed;top:0;left:0;background:#0d6efd;padding-top:2rem;color:#fff;}
.sidebar a{display:block;color:#fff;padding:12px 20px;text-decoration:none;margin-bottom:0.5rem;border-radius:8px;transition:0.2s;}
.sidebar a:hover{background:#084298;}
.sidebar h4{text-align:center;margin-bottom:2rem;color:#ffd369;}
.top-navbar{background:#fff;padding:1rem 2rem;margin-left:220px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 2px 8px rgba(0,0,0,0.1);position:sticky;top:0;z-index:1000;}
.main-content{margin-left:220px;padding:2rem;}
.card{border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.1);margin-bottom:2rem;}
</style>
</head>
<body>

<div class="sidebar">
  <h4>Admin</h4>
  <a href="#">Dashboard</a>
  <a href="#">Settings</a>
  <a href="logout.php">Logout</a>
</div>

<div class="top-navbar">
  <h5>Settings</h5>
  <div>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></div>
</div>

<div class="main-content container py-4">

<?php if($success): ?>
<div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<?php if($error): ?>
<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="row g-4">
  <!-- Update Profile -->
  <div class="col-md-6">
    <div class="card p-4">
      <h5>Update Profile</h5>
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Role</label>
          <input type="text" class="form-control" value="<?= htmlspecialchars($user['role']) ?>" disabled>
        </div>
        <button type="submit" name="update_profile" class="btn btn-primary w-100">Update Profile</button>
      </form>
    </div>
  </div>

  <!-- Change Password -->
  <div class="col-md-6">
    <div class="card p-4">
      <h5>Change Password</h5>
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Current Password</label>
          <input type="password" class="form-control" name="current_password" required>
        </div>
        <div class="mb-3">
          <label class="form-label">New Password</label>
          <input type="password" class="form-control" name="new_password" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Confirm Password</label>
          <input type="password" class="form-control" name="confirm_password" required>
        </div>
        <button type="submit" name="change_password" class="btn btn-warning w-100">Change Password</button>
      </form>
    </div>
  </div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
