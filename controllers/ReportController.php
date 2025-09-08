<?php
require_once __DIR__ . '/../models/Report.php';

class ReportController {
    private $reportModel;

    public function __construct($conn){
        $this->reportModel = new Report($conn);
    }

    public function index(){
        $registrationsByEvent = $this->reportModel->getRegistrationsByEvent();
        $eventStatus = $this->reportModel->getEventStatus();
        $recentRegistrations = $this->reportModel->getRecentRegistrations();

        require __DIR__ . '/../views/Admin/ReportsDashboard.php';
    }
}
