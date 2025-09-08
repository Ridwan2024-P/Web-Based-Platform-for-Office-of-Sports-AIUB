<?php
class Event {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM events ORDER BY date ASC");
        return $stmt;
    }

    public function add($name, $date, $venue, $participants, $status) {
        $stmt = $this->conn->prepare("INSERT INTO events (name, date, venue, participants, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $name, $date, $venue, $participants, $status);
        return $stmt->execute();
    }

    public function edit($id, $name, $date, $venue, $participants, $status) {
        $stmt = $this->conn->prepare("UPDATE events SET name=?, date=?, venue=?, participants=?, status=? WHERE id=?");
        $stmt->bind_param("sssisi", $name, $date, $venue, $participants, $status, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM events WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
