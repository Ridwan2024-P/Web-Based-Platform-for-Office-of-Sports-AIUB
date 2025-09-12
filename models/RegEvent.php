<?php
require_once 'models/Db.php';

class RegEvent {
    private $conn;

    public function __construct() {
        $this->conn = (new Db())->conn;
    }

    public function getActiveEvents() {
        return $this->conn->query("SELECT * FROM events WHERE status='Active' ORDER BY date ASC");
    }

    public function getEventName($event_id) {
        $stmt = $this->conn->prepare("SELECT name FROM events WHERE id=?");
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function isAlreadyRegistered($user_id, $event_id) {
        $stmt = $this->conn->prepare("SELECT id FROM registrations WHERE user_id=? AND event_id=?");
        $stmt->bind_param("ii", $user_id, $event_id);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function registerEvent($user_id, $event_id, $event_name) {
        $stmt = $this->conn->prepare(
            "INSERT INTO registrations (user_id, event_id, event_name, registration_date, status) 
             VALUES (?, ?, ?, CURDATE(), 'Pending')"
        );
        $stmt->bind_param("iis", $user_id, $event_id, $event_name);
        return $stmt->execute();
    }

    public function getHistory($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM registrations WHERE user_id=? ORDER BY registration_date DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();
    }
}
