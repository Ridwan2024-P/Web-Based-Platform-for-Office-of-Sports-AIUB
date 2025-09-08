<?php

require_once __DIR__ . '/../models/User.php';

class AdminController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }


    public function settings() {
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
            header("Location: index.php?action=login");
            exit;
        }

        $user = $this->userModel->getById($_SESSION['user_id']);
        $success = $_GET['success'] ?? '';
        $error = $_GET['error'] ?? '';

        require_once __DIR__ . '/../views/Admin/Settings.php';
    }

  
    public function updateProfile() {
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
            header("Location: index.php?action=login");
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $id = $_SESSION['user_id'];

            $updated = $this->userModel->updatee($id, $username, $email);

            if($updated) {
                header("Location: index.php?action=settings&success=Profile updated successfully");
                exit;
            } else {
                header("Location: index.php?action=settings&error=Failed to update profile");
                exit;
            }
        }
    }

    // Change Password
    public function changePassword() {
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
            header("Location: index.php?action=login");
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current = $_POST['current_password'];
            $new = $_POST['new_password'];
            $confirm = $_POST['confirm_password'];
            $id = $_SESSION['user_id'];

            $user = $this->userModel->getById($id);

            if(!password_verify($current, $user['password'])) {
                header("Location: index.php?action=settings&error=Current password is incorrect");
                exit;
            }

            if($new !== $confirm) {
                header("Location: index.php?action=settings&error=New password and confirm password do not match");
                exit;
            }

            $hashed = password_hash($new, PASSWORD_DEFAULT);
            $updated = $this->userModel->updatePassword($id, $hashed);

            if($updated) {
                header("Location: index.php?action=settings&success=Password changed successfully");
                exit;
            } else {
                header("Location: index.php?action/settings&error=Failed to change password");
                exit;
            }
        }
    }
}
