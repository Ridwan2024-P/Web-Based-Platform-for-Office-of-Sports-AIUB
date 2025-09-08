<?php
require_once __DIR__ . '/../models/User.php';

class UserController {
    public $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function indexmanager() {
        $users = $this->userModel->getAll();
        include __DIR__ . '/../views/Admin/ManageUser.php';
    }

    public function addUser() {
        if(isset($_POST['addUser'])){
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $role = $_POST['role'];
            $status = $_POST['status'];

            $this->userModel->add($username, $email, $password, $role, $status);
            header("Location: index.php?action=manageUsers");
        }
    }

    public function editUser() {
        if(isset($_POST['editUser'])){
            $id = $_POST['id'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $role = $_POST['role'];
            $status = $_POST['status'];
            $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

            $this->userModel->update($id, $username, $email, $password, $role, $status);
             header("Location: index.php?action=manageUsers");
        }
    }

    public function deleteUser() {
        if(isset($_GET['delete'])){
            $id = $_GET['delete'];
            $this->userModel->delete($id);
             header("Location: index.php?action=manageUsers");
        }
    }
}
