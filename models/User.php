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

    public function register($username, $email, $password, $role = "user") {
        $username = $this->conn->real_escape_string($username);
        $email = $this->conn->real_escape_string($email);
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (username, email, password, role, status) 
                VALUES ('$username', '$email', '$passwordHash', '$role', 1)";
        return $this->conn->query($sql);
    }
}
