<?php
require_once 'models/Registration.php';

class RegistrationController {
    private $registrationModel;

    public function __construct($db) {
        $this->registrationModel = new Registration($db);
    }

    public function index() {
        $registrations = $this->registrationModel->getAll();
        include 'views/Admin/RegistrationDashboard.php';
    }

    
    public function action() {
        if(isset($_POST['action'], $_POST['id'])) {
            $status = $_POST['action'] === 'approve' ? 'Approved' : 'Rejected';
            $this->registrationModel->updateStatus($_POST['id'], $status);
        }
        header("Location: ?action=manageRegistrations");
        exit;
    }
}
