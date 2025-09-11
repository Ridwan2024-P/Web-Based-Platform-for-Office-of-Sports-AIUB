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
    $sql = "
        SELECT r.id, u.username AS student_name, u.email, r.event_name, r.registration_date, r.status
        FROM registrations r
        JOIN users u ON r.user_id = u.id
        ORDER BY r.registration_date DESC
        LIMIT $limit
    ";
    $res = $this->conn->query($sql);
    while($row = $res->fetch_assoc()){
        $data[] = $row;
    }
    return $data;
}

}
