<?php
require_once __DIR__ . "/../controllers/RegisterController.php";

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $controller = new RegisterController();
    $message = $controller->registerUser($_POST);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register – Sports Platform</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .card {
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
      padding: 2rem;
      width: 100%;
      max-width: 450px;
      background: #fff;
    }
    h3 {
      color: #0d6efd;
      font-weight: 600;
      text-align: center;
      margin-bottom: 1.5rem;
    }
    .form-control {
      border-radius: 12px;
      padding: 0.75rem 1rem;
      box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
    }
    .btn-primary {
      border-radius: 12px;
      background: linear-gradient(45deg, #0d6efd, #198754);
      font-weight: 600;
      padding: 0.75rem;
      transition: 0.3s;
    }
    .btn-primary:hover {
      transform: scale(1.05);
      background: linear-gradient(45deg, #198754, #0d6efd);
    }
    .role-input {
      background-color: #e9ecef;
      color: #495057;
    }
    .alert {
      border-radius: 12px;
    }
    .login-link {
      text-align: center;
      margin-top: 1rem;
      font-size: 0.9rem;
    }
    .login-link a {
      color: #0d6efd;
      text-decoration: none;
      font-weight: 600;
    }
    .login-link a:hover {
      text-decoration: underline;
    }
    .password-toggle {
      cursor: pointer;
      position: absolute;
      right: 1rem;
      top: 70%;
      transform: translateY(-50%);
      font-size: 1.1rem;
      color: #495057;
    }
    .position-relative {
      position: relative;
    }
  </style>
</head>
<body>

<div class="card">
  <h3>Create Account</h3>
  <?php echo $message; ?>
  <form method="POST" action="index.php?action=register">

    <div class="mb-3">
      <label class="form-label">Username</label>
      <input type="text" name="username" class="form-control" placeholder="Enter username" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" placeholder="Enter email" required>
    </div>
    <div class="mb-3 position-relative">
      <label class="form-label">Password</label>
      <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" required>
      <span class="password-toggle" onclick="togglePassword()">👁️</span>
    </div>
    <div class="mb-3">
      <label class="form-label">Role</label>
      <input type="text" name="role" class="form-control role-input" value="user" readonly>
    </div>
    <button type="submit" class="btn btn-primary w-100">Register</button>
  </form>
  <div class="login-link">
    Already have an account? <a href="index.php?action=login">Login</a>
  </div>
</div>

<script>
function togglePassword() {
  const passwordInput = document.getElementById('password');
  passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
