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

                   
                    setcookie("last_activity", time(), time() + 300, "/"); 

                    
                    switch ($user['role']) {
                        case 'admin':
                            header("Location: index.php?action=dashboard");
                            break;
                        case 'user':
                            header("Location: index.php?action=dashboardd");
                            break;
                        case 'volunteer':
                            header("Location: index.php?action=volunteerDashboard");
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

    
    public function checkTimeout() {
        if (isset($_SESSION['user_id'])) {
            if (isset($_COOKIE['last_activity'])) {
                $inactive = time() - $_COOKIE['last_activity'];
                if ($inactive > 300) { 
                    $this->logout();
                } else {
                   
                    setcookie("last_activity", time(), time() + 300, "/");
                }
            } else {
               
                $this->logout();
            }
        }
    }

   
    public function checkLogin() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
    }

  
    public function checkRole($roles) {
        if (!isset($_SESSION['role'])) {
            header("Location: index.php?action=login");
            exit();
        }

        if (!is_array($roles)) {
            $roles = [$roles];
        }

        if (!in_array(strtolower($_SESSION['role']), array_map('strtolower', $roles))) {
            header("Location: index.php?action=login");
            exit();
        }
    }


    public function logout() {

        session_unset();
        session_destroy();

        
        setcookie("last_activity", "", time() - 3600, "/");

        header("Location: index.php?action=login");
        exit;
    }

  
    public function dashboard() {
        $this->checkLogin();
        $this->checkTimeout();
        require_once __DIR__ . '/../views/Admin/adminDashboard.php';
    }
}
