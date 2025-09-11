<?php
session_start();
require_once 'models/Db.php'; // Database connection

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$conn = (new Db())->conn;

// Handle event registration
if(isset($_POST['register_event'])) {
    $event_id = $_POST['event_id'];
    $student_name = $_SESSION['username'];
    $email = $_SESSION['email'];

    // Get event name
    $stmt = $conn->prepare("SELECT name FROM events WHERE id=?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $event = $stmt->get_result()->fetch_assoc();
    $event_name = $event['name'];

    // Insert registration
    $stmt = $conn->prepare("INSERT INTO registrations (student_name,email,event_name,registration_date,status) VALUES (?,?,?,CURDATE(),'Pending')");
    $stmt->bind_param("sss", $student_name, $email, $event_name);
    $stmt->execute();

    $message = "Successfully registered for $event_name";
}

// Fetch upcoming events
$events_result = $conn->query("SELECT * FROM events WHERE status='Active' ORDER BY date ASC");

// Fetch participation history
$stmt = $conn->prepare("SELECT * FROM registrations WHERE email=? ORDER BY registration_date DESC");
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();
$history_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Dashboard – AIUB Sports</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
/* Sidebar */
body{font-family:sans-serif;background:#f4f6f9;}
.sidebar{height:100vh;width:220px;position:fixed;top:0;left:0;background:#0d6efd;padding-top:2rem;color:#fff;}
.sidebar a{display:block;color:#fff;padding:12px 20px;text-decoration:none;margin-bottom:0.5rem;border-radius:8px;transition:0.2s;}
.sidebar a:hover{background:#084298;}
.sidebar h4{text-align:center;margin-bottom:2rem;color:#ffd369;}
.top-navbar{background:#fff;padding:1rem 2rem;margin-left:220px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 2px 8px rgba(0,0,0,0.1);position:sticky;top:0;z-index:1000;}
.main-content{margin-left:220px;padding:2rem;}
.card{border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.1);margin-bottom:2rem;transition:transform 0.2s;}
.card:hover{transform:translateY(-5px);box-shadow:0 8px 20px rgba(0,0,0,0.15);}
.btn-custom{background:linear-gradient(45deg,#0d6efd,#198754);color:#fff;border:none;font-weight:bold;border-radius:8px;transition:0.3s;}
.btn-custom:hover{transform:scale(1.05);background:linear-gradient(45deg,#198754,#0d6efd);}
</style>
</head>
<body>

<div class="sidebar">
  <h4>User</h4>
  <a href="#">Dashboard</a>
  <a href="#">Upcoming Events</a>
  <a href="my_registrations.php">My Registrations</a>
  <a href="#">Profile</a>
  <a href="logout.php">Logout</a>
</div>

<div class="top-navbar">
  <div><h3 style="color:#0d6efd;">Student</h3></div>
  <h6>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></h6>
</div>

<div class="main-content">

<?php if(isset($message)): ?>
<div class="alert alert-success"><?= $message ?></div>
<?php endif; ?>

<h4 class="mb-3">Upcoming Sports Events</h4>
<div class="row">
<?php while($event = $events_result->fetch_assoc()): ?>
  <div class="col-md-4">
    <div class="card p-3">
      <h5><?= htmlspecialchars($event['name']) ?></h5>
      <p>Date: <?= htmlspecialchars($event['date']) ?></p>
      <p>Venue: <?= htmlspecialchars($event['venue']) ?></p>
      <form method="POST">
        <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
        <button class="btn btn-custom w-100" name="register_event">Register</button>
      </form>
    </div>
  </div>
<?php endwhile; ?>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
