<?php
require_once __DIR__ . '/../models/Dashboard.php';

class DashboardController {
    private $model;

    public function __construct($db) {
        $this->model = new Dashboard($db);
    }

    public function index() {
        $totalUsers = $this->model->getTotalUsers();
        $totalVolunteers = $this->model->getTotalVolunteers();
        $activeEvents = $this->model->getActiveEvents();
        $events = $this->model->getUpcomingEvents();

        include __DIR__ . '/../views/Admin/adminDashboard.php';
    }
}
