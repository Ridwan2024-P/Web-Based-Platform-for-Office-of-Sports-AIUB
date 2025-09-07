<?php
require_once __DIR__ . "/../models/User.php";

class RegisterController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function registerUser($formData) {
        $username = $formData['username'];
        $email = $formData['email'];
        $password = $formData['password'];

        if ($this->userModel->register($username, $email, $password)) {
            return "<div class='alert alert-success'>✅ Registration successful!</div>";
        } else {
            return "<div class='alert alert-danger'>❌ Error while registering!</div>";
        }
    }
}
