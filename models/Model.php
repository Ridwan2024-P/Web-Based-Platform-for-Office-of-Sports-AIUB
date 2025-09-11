<?php
class Model {
   private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }



    public function getVolunteers() {
        $result = $this->conn->query("SELECT id, username FROM users WHERE role='volunteer' ORDER BY username ASC");
        return $result;
    }

    public function getActiveEvents() {
        $result = $this->conn->query("SELECT id, name FROM events WHERE status='Active' ORDER BY name ASC");
        return $result;
    }

    public function addTask($volunteer_id, $event_id, $task_name, $task_date) {
        $stmt = $this->conn->prepare("SELECT name FROM events WHERE id=?");
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $event = $stmt->get_result()->fetch_assoc();
        if(!$event) return false;
        $event_name = $event['name'];

        $stmt = $this->conn->prepare("INSERT INTO volunteer_tasks (volunteer_id, event_name, task_name, task_date) VALUES (?,?,?,?)");
        $stmt->bind_param("isss", $volunteer_id, $event_name, $task_name, $task_date);
        return $stmt->execute();
    }

    public function addAnnouncement($message, $event_date = null) {
        $stmt = $this->conn->prepare("INSERT INTO announcements (message, event_date) VALUES (?,?)");
        $stmt->bind_param("ss", $message, $event_date);
        return $stmt->execute();
    }
    public function getTasks() {
    $sql = "SELECT vt.id, u.username AS volunteer_name, vt.event_name, vt.task_name, vt.task_date
            FROM volunteer_tasks vt
            JOIN users u ON vt.volunteer_id = u.id
            ORDER BY vt.task_date DESC";
    return $this->conn->query($sql);
}

public function getAnnouncements() {
    $sql = "SELECT * FROM announcements ORDER BY event_date DESC";
    return $this->conn->query($sql);
}

}
