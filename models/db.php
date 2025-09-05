<?php
class Db {
    public $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "rid");
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
}
