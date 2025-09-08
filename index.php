<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rid";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


require_once 'controllers/AuthController.php';
require_once 'controllers/RegisterController.php';
require_once 'controllers/UserController.php';
require_once 'controllers/RegistrationController.php';


$auth = new AuthController();
$register = new RegisterController();
$userController = new UserController();
$registrationController = new RegistrationController($conn); 


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


    case 'manageRegistrations': $registrationController->index(); break;
    case 'registrationAction': $registrationController->action(); break;

    default: $auth->login(); break;
}
