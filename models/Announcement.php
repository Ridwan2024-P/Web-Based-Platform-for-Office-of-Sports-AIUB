<?php
require_once 'Db.php';

class Announcement {
    private $conn;

    public function __construct() {
        $this->conn = (new Db())->conn;
    }

    public function getAll() {
        $result = $this->conn->query("SELECT * FROM announcements ORDER BY event_date ASC");
        return $result;
    }
}
