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
    

    // === New method: Get user by ID ===
    public function getById($id){
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }


    // === Change password method ===
    public function changePassword($id, $current, $new){
        $stmt = $this->conn->prepare("SELECT password FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();

        if(!password_verify($current, $res['password'])){
            return "Current password is incorrect";
        }

        $new_hashed = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt->bind_param("si", $new_hashed, $id);
        return $stmt->execute();
    }
    public function updatee($id, $username, $email, $param4 = null, $param5 = null, $param6 = null) {
    $stmt = $this->conn->prepare("UPDATE users SET username=?, email=? WHERE id=?");
    $stmt->bind_param("ssi", $username, $email, $id);
    return $stmt->execute();

}
public function updatePassword($id, $hashedPassword) {
    $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashedPassword, $id);
    return $stmt->execute();
}


}
