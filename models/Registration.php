<?php
class Registration {
    private $conn;
    private $table = 'registrations';

    public function __construct($db) {
        $this->conn = $db;
    }

  public function getAll() {
    $sql = "
        SELECT r.id, r.event_name, r.registration_date, r.status,
               u.username AS student_name, u.email
        FROM {$this->table} r
        JOIN users u ON r.user_id = u.id
        ORDER BY r.registration_date DESC
    ";
    return $this->conn->query($sql);
}


    public function updateStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET status=? WHERE id=?");
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }
}
