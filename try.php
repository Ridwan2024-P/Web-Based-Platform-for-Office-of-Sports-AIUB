<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db   = "rid";

$conn = new mysqli($host, $user, $pass, $db);
if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

// যদি লগইন না করা থাকে
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Individual Registration
if (isset($_POST['register_event'])) {
    $event_id = intval($_POST['event_id']);

    // Check if already registered
    $check = $conn->query("SELECT * FROM registrations WHERE user_id=$user_id AND event_id=$event_id");
    if ($check->num_rows > 0) {
        $message = "You have already registered for this event!";
    } else {
        $sql = "INSERT INTO registrations (user_id, event_id, status) 
                VALUES ($user_id, $event_id, 'Pending')";
        if ($conn->query($sql)) {
            $message = "Registered successfully for the event!";
        } else {
            $message = "Registration failed: " . $conn->error;
        }
    }
}

// Team Registration
if (isset($_POST['register_team'])) {
    $event_id = intval($_POST['event_id']);

    // Check if already registered
    $check = $conn->query("SELECT * FROM registrations WHERE user_id=$user_id AND event_id=$event_id");
    if ($check->num_rows > 0) {
        $message = "You have already registered for this event!";
    } else {
        $team_name = $conn->real_escape_string($_POST['team_name']);
        $players = $_POST['players'];
        $student_ids = $_POST['student_ids'];

        // Create team
        $conn->query("INSERT INTO teams (team_name, event_id, created_by) 
                      VALUES ('$team_name', $event_id, $user_id)");
        $team_id = $conn->insert_id;

        // Add players
        foreach ($players as $i => $player_name) {
            $pname = $conn->real_escape_string($player_name);
            $sid = $conn->real_escape_string($student_ids[$i]);
            $conn->query("INSERT INTO players (team_id, player_name, student_id) 
                          VALUES ($team_id, '$pname', '$sid')");
        }

        // Register team
        $conn->query("INSERT INTO registrations (user_id, event_id, team_id, status) 
                      VALUES ($user_id, $event_id, $team_id, 'Pending')");
        $message = "Team registered successfully!";
    }
}

// Fetch upcoming events
$events = $conn->query("SELECT * FROM events WHERE date >= CURDATE() ORDER BY date ASC");

// Fetch history with players
$history = $conn->query("
    SELECT r.id AS reg_id, e.name AS event_name, r.registration_date, r.status, 
           t.team_name, p.player_name, p.student_id
    FROM registrations r
    JOIN events e ON r.event_id = e.id
    LEFT JOIN teams t ON r.team_id = t.id
    LEFT JOIN players p ON t.id = p.team_id
    WHERE r.user_id = $user_id
    ORDER BY r.registration_date DESC, t.id ASC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard – AIUB Sports</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
   body {
      font-family: 'Poppins', sans-serif;
      background: #f4f6f9;
    }

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

<div class="sidebar">
  <h4>User</h4>
  <a href="dashboard.php">Dashboard</a>
  <a href="#">Upcoming Events</a>
  <a href="#">My Registrations</a>
  <a href="logout.php">Logout</a>
</div>

<div class="main-content">
  <div class="top-navbar">
    <h3 style="color:#0d6efd;">Student</h3>
    <h6>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></h6>
  </div>

  <?php if($message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <h4 class="mb-3">Upcoming Sports Events</h4>
  <div class="row">
    <?php while($event = $events->fetch_assoc()): ?>
      <div class="col-md-4">
        <div class="card p-3 mb-3">
          <h5><?= htmlspecialchars($event['name']) ?></h5>
          <p>Date: <?= date("d M, Y", strtotime($event['date'])) ?></p>
          <p>Venue: <?= htmlspecialchars($event['venue']) ?></p>

          <!-- Individual Registration -->
          <form method="POST">
            <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
            <button class="btn btn-primary w-100" name="register_event">Register Solo</button>
          </form>

          <!-- Team Registration Button -->
          <button class="btn btn-success w-100 mt-2" data-bs-toggle="modal" data-bs-target="#teamModal<?= $event['id'] ?>">
            Register as Team
          </button>
        </div>
      </div>

      <!-- Team Modal -->
      <div class="modal fade" id="teamModal<?= $event['id'] ?>" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <form method="POST">
              <div class="modal-header">
                <h5 class="modal-title">Register Team for <?= htmlspecialchars($event['name']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                <div class="mb-3">
                  <label>Team Name</label>
                  <input type="text" class="form-control" name="team_name" required>
                </div>
                <div id="player-fields-<?= $event['id'] ?>">
                  <div class="mb-3">
                    <label>Player Name</label>
                    <input type="text" class="form-control" name="players[]" required>
                    <label>Student ID</label>
                    <input type="text" class="form-control" name="student_ids[]" required>
                  </div>
                </div>
                <button type="button" class="btn btn-sm btn-secondary" onclick="addPlayer('<?= $event['id'] ?>')">+ Add Player</button>
              </div>
              <div class="modal-footer">
                <button class="btn btn-success" name="register_team">Register Team</button>
              </div>
            </form>
          </div>
        </div>
      </div>

    <?php endwhile; ?>
  </div>

  <div class="card p-3 mt-5">
    <h4>My Participation History</h4>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Event</th>
                    <th>Team / Players</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Fetch registrations grouped properly
            $reg_query = $conn->query("
                SELECT r.id AS reg_id, e.name AS event_name, r.registration_date, r.status, r.team_id, t.team_name
                FROM registrations r
                JOIN events e ON r.event_id = e.id
                LEFT JOIN teams t ON r.team_id = t.id
                WHERE r.user_id = $user_id
                ORDER BY r.registration_date DESC
            ");

            $i = 1;
            while($reg = $reg_query->fetch_assoc()):
                echo "<tr>";
                echo "<td>".$i++."</td>";
                echo "<td>".htmlspecialchars($reg['event_name'])."</td>";

                if($reg['team_id']) {
                    // Team registration, fetch all players
                    $team_players = $conn->query("SELECT player_name, student_id FROM players WHERE team_id=".$reg['team_id']);
                    echo "<td>";
                    echo "<strong>".htmlspecialchars($reg['team_name'])."</strong><br>";
                    while($player = $team_players->fetch_assoc()){
                        echo htmlspecialchars($player['player_name'])." (".htmlspecialchars($player['student_id']).")<br>";
                    }
                    echo "</td>";
                } else {
                    echo "<td>Individual</td>";
                }

                echo "<td>".$reg['registration_date']."</td>";
                echo "<td>".$reg['status']."</td>";
                echo "</tr>";
            endwhile;
            ?>
            </tbody>
        </table>
    </div>
</div>


<script>
function addPlayer(eventId) {
  let container = document.getElementById("player-fields-" + eventId);
  let div = document.createElement("div");
  div.classList.add("mb-3");
  div.innerHTML = `
    <label>Player Name</label>
    <input type="text" class="form-control" name="players[]" required>
    <label>Student ID</label>
    <input type="text" class="form-control" name="student_ids[]" required>
  `;
  container.appendChild(div);
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
