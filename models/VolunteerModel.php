<?php
class VolunteerModel {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "volunteer_db");
        if($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Fetch tasks assigned to a volunteer
    public function getTasksByVolunteer($volunteer_id) {
        $stmt = $this->conn->prepare("SELECT * FROM volunteer_tasks WHERE volunteer_id=? ORDER BY task_date ASC");
        $stmt->bind_param("i", $volunteer_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Fetch all announcements
    public function getAnnouncements() {
        return $this->conn->query("SELECT * FROM announcements ORDER BY event_date ASC");
    }

    // Update task status
    public function updateTaskStatus($task_id, $volunteer_id, $status) {
        $stmt = $this->conn->prepare("UPDATE volunteer_tasks SET status=? WHERE id=? AND volunteer_id=?");
        $stmt->bind_param("sii", $status, $task_id, $volunteer_id);
        return $stmt->execute();
    }
}
