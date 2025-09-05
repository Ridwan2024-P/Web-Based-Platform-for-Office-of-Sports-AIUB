<?php
session_start();
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        $error = '';
        if(isset($_POST['login'])){
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            $user = $this->userModel->getByEmail($email);

            if($user){
                if(password_verify($password, $user['password'])){
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    header("Location: index.php?action=dashboard");
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
