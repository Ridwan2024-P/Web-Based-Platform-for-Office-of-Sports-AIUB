<?php
class Report {
    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }

    // Registrations by Event
    public function getRegistrationsByEvent(){
        $data = [];
        $res = $this->conn->query("SELECT event_name, COUNT(id) AS total FROM registrations GROUP BY event_name");
        while($row = $res->fetch_assoc()){
            $data[] = $row;
        }
        return $data;
    }

    // Event Status Distribution
    public function getEventStatus(){
        $data = [];
        $res = $this->conn->query("SELECT status, COUNT(id) AS total FROM events GROUP BY status");
        while($row = $res->fetch_assoc()){
            $data[] = $row;
        }
        return $data;
    }

    // Recent Registrations
    public function getRecentRegistrations($limit = 10){
        $data = [];
        $res = $this->conn->query("SELECT id, student_name, email, event_name, registration_date, status FROM registrations ORDER BY registration_date DESC LIMIT $limit");
        while($row = $res->fetch_assoc()){
            $data[] = $row;
        }
        return $data;
    }
}
