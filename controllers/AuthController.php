<?php

require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

   public function login() {
    $error = '';
    if (isset($_POST['login'])) {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        $user = $this->userModel->getByEmail($email);

        if ($user) {
            
            if ($user['status'] !== 1) {
                $error = "Your account is inactive. Please contact admin.";
            } elseif (password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                switch ($user['role']) {
                    case 'admin':
                        header("Location: index.php?action=dashboard");
                        break;

                    case 'user':
                        header("Location: index.php?action=dashboardd");
                        break;

                    case 'volunteer':
                        header("Location:index.php?action=volunteerDashboard");
                        break;

                    default:
                        header("Location: index.php");
                        break;
                }
                exit;

            } else {
                $error = "Invalid password!";
            }
        } else {
            $error = "Email not found!";
        }
    }

    require_once __DIR__ . '/../views/login.php';
}



    public function dashboard() {
        if(!isset($_SESSION['user_id'])){
            header("Location: index.php?action=login");
            exit;
        }
        require_once __DIR__ . '/../views/Admin/adminDashboard.php';
        
    }

    public function logout() {
        session_unset();
        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }
    
}
