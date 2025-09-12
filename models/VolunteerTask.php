<?php
require_once 'Db.php';

class VolunteerTask {
    private $conn;

    public function __construct() {
        $this->conn = (new Db())->conn;
    }

    public function getTasksByVolunteer($volunteer_id) {
        $stmt = $this->conn->prepare("SELECT * FROM volunteer_tasks WHERE volunteer_id=? ORDER BY task_date ASC");
        $stmt->bind_param("i", $volunteer_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function markCompleted($task_id, $volunteer_id) {
        $stmt = $this->conn->prepare("UPDATE volunteer_tasks SET status='Completed' WHERE id=? AND volunteer_id=?");
        $stmt->bind_param("ii", $task_id, $volunteer_id);
        return $stmt->execute();
    }
}
