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
       
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email=? OR username=?");
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            return false; 
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password, role, status) VALUES (?, ?, ?, ?, 1)");
        $stmt->bind_param("ssss", $username, $email, $passwordHash, $role);

        return $stmt->execute();
    }
      public function getAll() {
        $result = $this->conn->query("SELECT * FROM users");
        return $result;
    }

    public function add($username, $email, $password, $role, $status) {
        $stmt = $this->conn->prepare("INSERT INTO users (username,email,password,role,status) VALUES (?,?,?,?,?)");
        $stmt->bind_param("ssssi", $username, $email, $password, $role, $status);
        return $stmt->execute();
    }

    public function update($id, $username, $email, $password, $role, $status) {
        if(!empty($password)){
            $stmt = $this->conn->prepare("UPDATE users SET username=?, email=?, password=?, role=?, status=? WHERE id=?");
            $stmt->bind_param("ssssii", $username, $email, $password, $role, $status, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE users SET username=?, email=?, role=?, status=? WHERE id=?");
            $stmt->bind_param("sssii", $username, $email, $role, $status, $id);
        }
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
