<?php
session_start();
require_once 'models/Db.php'; // Database connection

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$conn = (new Db())->conn;

// Handle cancel registration
if(isset($_POST['cancel_registration'])) {
    $registration_id = $_POST['registration_id'];

    $stmt = $conn->prepare("DELETE FROM registrations WHERE id=? AND email=?");
    $stmt->bind_param("is", $registration_id, $_SESSION['email']);
    $stmt->execute();

    $message = "Registration cancelled successfully";
}

// Fetch user registrations
$stmt = $conn->prepare("SELECT r.id, r.event_name, r.status, e.date, e.venue 
                        FROM registrations r 
                        LEFT JOIN events e ON r.event_name = e.name 
                        WHERE r.email=? 
                        ORDER BY e.date ASC");

$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();
$registrations = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Registrations – AIUB Sports</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family: 'Poppins', sans-serif; background: #f4f6f9; }
/* Sidebar */
.sidebar { height: 100vh; width: 220px; position: fixed; top: 0; left: 0; background-color: #0d6efd; padding-top: 2rem; color: #fff; }
.sidebar a { display: block; color: #fff; padding: 12px 20px; text-decoration: none; margin-bottom: 0.5rem; border-radius: 8px; transition: 0.2s; }
.sidebar a:hover { background-color: #084298; }
.sidebar h4 { text-align: center; margin-bottom: 2rem; color: #ffd369; }
/* Top Navbar */
.top-navbar { background-color: #fff; padding: 1rem 2rem; margin-left: 220px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 1000; }
.top-navbar h5 { margin: 0; color: #0d6efd; }
/* Main content */
.main-content { margin-left: 220px; padding: 2rem; }
.card { border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 2rem; }
.table thead th { background-color: #0d6efd; color: #fff; }
.badge-status { font-size: 0.9rem; }
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h4>User</h4>
  <a href="dashboard.php">Dashboard</a>
  <a href="upcoming_events.php">Upcoming Events</a>
  <a href="my_registrations.php">My Registrations</a>
  <a href="profile.php">Profile</a>
  <a href="logout.php">Logout</a>
</div>

<!-- Top Navbar -->
<div class="top-navbar">
  <h5>My Registrations</h5>
  <div>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></div>
</div>

<!-- Main Content -->
<div class="main-content">

<?php if(isset($message)): ?>
<div class="alert alert-success"><?= $message ?></div>
<?php endif; ?>

<div class="card p-3">
  <h4 class="mb-3">Registered Events</h4>
  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Event Name</th>
          <th>Date</th>
          <th>Venue</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $i = 1;
        while($row = $registrations->fetch_assoc()):
            $status_class = $row['status'] == 'Registered' ? 'bg-success' : 'bg-warning';
        ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= htmlspecialchars($row['event_name']) ?></td>
          <td><?= htmlspecialchars($row['date']) ?></td>
          <td><?= htmlspecialchars($row['venue']) ?></td>
          <td><span class="badge <?= $status_class ?> badge-status"><?= htmlspecialchars($row['status']) ?></span></td>
          <td>
            <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this registration?');">
              <input type="hidden" name="registration_id" value="<?= $row['id'] ?>">
              <button class="btn btn-sm btn-danger" name="cancel_registration">Cancel</button>
            </form>
          </td>
        </tr>
        <?php endwhile; ?>
        <?php if($registrations->num_rows == 0): ?>
        <tr>
          <td colspan="6" class="text-center">No registrations found.</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
