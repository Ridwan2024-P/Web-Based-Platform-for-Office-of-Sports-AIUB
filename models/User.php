<?php
require_once 'Db.php';

class User {
    private $conn;

    public function __construct() {
        $db = new Db();
        $this->conn = $db->conn;
    }

    public function getByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
