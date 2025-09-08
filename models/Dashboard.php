<?php
class Dashboard {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getTotalUsers() {
        $result = $this->conn->query("SELECT COUNT(*) AS totalUsers FROM users WHERE role='user'");
        return $result->fetch_assoc()['totalUsers'];
    }

    public function getTotalVolunteers() {
        $result = $this->conn->query("SELECT COUNT(*) AS totalVolunteers FROM users WHERE role='volunteer'");
        return $result->fetch_assoc()['totalVolunteers'];
    }

    public function getActiveEvents() {
        $result = $this->conn->query("SELECT COUNT(*) AS activeEvents FROM events WHERE status='Active'");
        return $result->fetch_assoc()['activeEvents'];
    }

    public function getUpcomingEvents() {
        return $this->conn->query("SELECT * FROM events ORDER BY date ASC LIMIT 5");
    }
}
