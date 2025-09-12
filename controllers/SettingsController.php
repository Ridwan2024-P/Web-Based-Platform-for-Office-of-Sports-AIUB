<?php
session_start();
require_once __DIR__ . '/../models/User.php';

class SettingsController {
    private $userModel;

    public function __construct(){
        $this->userModel = new User();
    }

    public function index(){
    
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user'){
            header("Location: index.php?action=login");
            exit;
        }

        $user = $this->userModel->getById($_SESSION['user_id']);

        $success = $_GET['success'] ?? '';
        $error = $_GET['error'] ?? '';

        require_once __DIR__ . '/../views/Admin/Settings.php';
    }
}
?>
