<?php
// === DB CONNECTION ===
$host = "localhost";
$user = "root";
$pass = "";
$db   = "rid";

$conn = new mysqli($host, $user, $pass, $db);
if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

// === INCLUDE CONTROLLERS ===
require_once 'controllers/AuthController.php';
require_once 'controllers/RegisterController.php';
require_once 'controllers/UserController.php';
require_once 'controllers/RegistrationController.php';
require_once 'controllers/EventController.php';
require_once 'controllers/ReportController.php';

// === INITIALIZE CONTROLLERS ===
$auth = new AuthController($conn);
$register = new RegisterController($conn);
$userController = new UserController($conn);
$registrationController = new RegistrationController($conn);
$eventController = new EventController($conn);
$reportController = new ReportController($conn);

// === ROUTING ===
$action = $_GET['action'] ?? 'login';

switch($action){
    // Authentication
    case 'login': $auth->login(); break;
    case 'register': $register->register(); break;
    case 'dashboard': $auth->dashboard(); break;
    case 'logout': $auth->logout(); break;

    // User Management
    case 'manageUsers': $userController->indexmanager(); break;
    case 'add': $userController->addUser(); break;
    case 'edit': $userController->editUser(); break;
    case 'delete': $userController->deleteUser(); break;

    // Registrations
    case 'manageRegistrations': $registrationController->index(); break;
    case 'registrationAction': $registrationController->action(); break;

    // Events Management
    case 'manageEvents':
    case 'addEvent':
    case 'editEvent':
    case 'deleteEvent':
        $eventController->handleRequest();
        break;

    // Reports
    case 'reports':
        $reportController->index();
        break;

    // Default: login page
    default: $auth->login(); break;
}
