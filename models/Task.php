<?php
require_once 'Db.php';

class Task {
    private $conn;

    public function __construct() {
        $this->conn = (new Db())->conn;
    }

    public function getByVolunteer($volunteer_id) {
        $stmt = $this->conn->prepare("SELECT * FROM volunteer_tasks WHERE volunteer_id=? ORDER BY task_date ASC");
        $stmt->bind_param("i", $volunteer_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function updateStatus($task_id, $volunteer_id, $status) {
        $stmt = $this->conn->prepare("UPDATE volunteer_tasks SET status=? WHERE id=? AND volunteer_id=?");
        $stmt->bind_param("sii", $status, $task_id, $volunteer_id);
        return $stmt->execute();
    }
}
