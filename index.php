<?php
require_once 'controllers/AuthController.php';
require_once 'controllers/RegisterController.php';
require_once 'controllers/UserController.php';

$auth = new AuthController();
$register = new RegisterController();
$userController = new UserController();

$action = $_GET['action'] ?? 'login';

switch($action){
    case 'login': $auth->login(); break;
    case 'register': $register->register(); break;
    case 'dashboard': $auth->dashboard(); break;
    case 'logout': $auth->logout(); break;

   case 'manageUsers': $userController->indexmanager(); break;
    case 'add': $userController->addUser(); break;
    case 'edit': $userController->editUser(); break;
    case 'delete': $userController->deleteUser(); break;

    default: $auth->login(); break;
}
