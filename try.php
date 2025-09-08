<?php
session_start();


$host = "localhost";
$user = "root";       
$pass = "";          
$db   = "rid";        

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ===== AUTO CREATE DEFAULT ADMIN =====
$result = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='admin'");
$row = $result->fetch_assoc();
if($row['total'] == 0){
    $defaultPassword = password_hash("admin123", PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, status, created_at, updated_at) VALUES (?, ?, ?, 'admin', 1, NOW(), NOW())");
    $stmt->bind_param("sss", $username, $email, $defaultPassword);
    $username = "Admin User";
    $email = "admin@example.com";
    $stmt->execute();
    $stmt->close();
}

// ===== VARIABLES =====
$loginMsg = "";
$profileMsg = "";
$passwordMsg = "";

// ===== LOGIN =====
if(isset($_POST['login'])){
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, email, password, role, status FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id, $username, $userEmail, $hashedPassword, $role, $status);
    $stmt->fetch();
    $stmt->close();

    if($id && password_verify($password, $hashedPassword) && $status == 1 && $role == 'admin'){
        $_SESSION['admin_id'] = $id;
        $_SESSION['admin_name'] = $username;
        $_SESSION['admin_email'] = $userEmail;
        $_SESSION['admin_role'] = $role;
        header("Location: ".$_SERVER['PHP_SELF']); // reload page
        exit();
    } else {
        $loginMsg = "<div class='alert alert-danger'>Invalid credentials or inactive account.</div>";
    }
}

// ===== SETTINGS PAGE =====
if(isset($_SESSION['admin_id'])){
    $adminId = $_SESSION['admin_id'];
    $adminName = $_SESSION['admin_name'];
    $adminEmail = $_SESSION['admin_email'];
    $adminRole = $_SESSION['admin_role'];

    // Update Profile
    if(isset($_POST['updateProfile'])){
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);

        if($name != "" && $email != ""){
            $stmt = $conn->prepare("UPDATE users SET username=?, email=?, updated_at=NOW() WHERE id=?");
            $stmt->bind_param("ssi", $name, $email, $adminId);
            if($stmt->execute()){
                $_SESSION['admin_name'] = $name;
                $_SESSION['admin_email'] = $email;
                $adminName = $name;
                $adminEmail = $email;
                $profileMsg = "<div class='alert alert-success'>Profile updated successfully!</div>";
            } else {
                $profileMsg = "<div class='alert alert-danger'>Failed to update profile.</div>";
            }
        } else {
            $profileMsg = "<div class='alert alert-warning'>Name and Email cannot be empty.</div>";
        }
    }

    // Change Password
    if(isset($_POST['changePassword'])){
        $current = $_POST['currentPassword'];
        $new = $_POST['newPassword'];
        $confirm = $_POST['confirmPassword'];

        $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
        $stmt->bind_param("i", $adminId);
        $stmt->execute();
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        $stmt->close();

        if(password_verify($current, $hashedPassword)){
            if($new === $confirm){
                $newHashed = password_hash($new, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET password=?, updated_at=NOW() WHERE id=?");
                $stmt->bind_param("si", $newHashed, $adminId);
                if($stmt->execute()){
                    $passwordMsg = "<div class='alert alert-success'>Password changed successfully!</div>";
                } else {
                    $passwordMsg = "<div class='alert alert-danger'>Failed to change password.</div>";
                }
            } else {
                $passwordMsg = "<div class='alert alert-warning'>New password and confirm password do not match.</div>";
            }
        } else {
            $passwordMsg = "<div class='alert alert-danger'>Current password is incorrect.</div>";
        }
    }
}

// ===== LOGOUT =====
if(isset($_GET['logout'])){
    session_destroy();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login / Settings</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
      font-family: 'Poppins', sans-serif;
      background: #f4f6f9;
    }

    /* Sidebar */
    .sidebar {
      height: 100vh;
      width: 220px;
      position: fixed;
      top: 0;
      left: 0;
      background-color: #0d6efd;
      padding-top: 2rem;
      color: #fff;
    }
    .sidebar a {
      display: block;
      color: #fff;
      padding: 12px 20px;
      text-decoration: none;
      margin-bottom: 0.5rem;
      border-radius: 8px;
      transition: 0.2s;
    }
    .sidebar a:hover {
      background-color: #084298;
    }
    .sidebar h4 {
      text-align: center;
      margin-bottom: 2rem;
      color: #ffd369;
    }

    .top-navbar {
      background-color: #fff;
      padding: 1rem 2rem;
      margin-left: 220px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      position: sticky;
      top: 0;
      z-index: 1000;
    }
    .top-navbar h5 {
      margin: 0;
      color: #0d6efd;
    }
    .main-content {
      margin-left: 220px;
      padding: 2rem;
    }

    .card {
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .btn-action {
      margin-right: 5px;
    }

    .table thead th {
      background-color: #0d6efd;
      color: #fff;
    }

    .search-input {
      max-width: 300px;
    }
</style>
</head>
<body>

<?php if(!isset($_SESSION['admin_id'])): ?>
<div class="container mt-5">
<div class="card p-4">
<h3 class="mb-4">Admin Login</h3>
<?php echo $loginMsg; ?>
<form method="POST">
<div class="mb-3">
<label for="email" class="form-label">Email</label>
<input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
</div>
<div class="mb-3">
<label for="password" class="form-label">Password</label>
<input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
</div>
<button type="submit" name="login" class="btn btn-custom w-100">Login</button>
</form>
</div>
</div>

<?php else: ?>
<div class="sidebar">
<h4>Admin</h4>
<a href="?">Dashboard</a>
<a href="?">Manage Users</a>
<a href="?">Manage Events</a>
<a href="?">Registrations</a>
<a href="?">Reports</a>
<a href="?">Settings</a>
<a href="?logout=1">Logout</a>
</div>

<div style="flex:1;">
<div class="top-navbar">
<h5>Settings</h5>
<div>Welcome, <?php echo htmlspecialchars($adminName); ?></div>
</div>

<div class="main-content container">
<div class="row">
<div class="col-md-6">
<div class="card p-4 mb-4">
<h5>Update Profile</h5>
<?php echo $profileMsg; ?>
<form method="POST">
<div class="mb-3">
<label for="name" class="form-label">Full Name</label>
<input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($adminName); ?>">
</div>
<div class="mb-3">
<label for="email" class="form-label">Email</label>
<input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($adminEmail); ?>">
</div>
<div class="mb-3">
<label for="role" class="form-label">Role</label>
<input type="text" class="form-control" id="role" value="<?php echo htmlspecialchars($adminRole); ?>" disabled>
</div>
<button type="submit" name="updateProfile" class="btn btn-custom w-100">Update Profile</button>
</form>
</div>
</div>

<div class="col-md-6">
<div class="card p-4 mb-4">
<h5>Change Password</h5>
<?php echo $passwordMsg; ?>
<form method="POST">
<div class="mb-3">
<label for="currentPassword" class="form-label">Current Password</label>
<input type="password" class="form-control" id="currentPassword" name="currentPassword" placeholder="Enter current password">
</div>
<div class="mb-3">
<label for="newPassword" class="form-label">New Password</label>
<input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="Enter new password">
</div>
<div class="mb-3">
<label for="confirmPassword" class="form-label">Confirm Password</label>
<input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm new password">
</div>
<button type="submit" name="changePassword" class="btn btn-custom w-100">Change Password</button>
</form>
</div>
</div>
</div>
</div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
