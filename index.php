<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db   = "rid";

$conn = new mysqli($host, $user, $pass, $db);
if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/RegisterController.php';
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/RegistrationController.php';
require_once __DIR__ . '/controllers/EventController.php';
require_once __DIR__ . '/controllers/ReportController.php';
require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/controllers/AdminTasksController.php';
require_once __DIR__ . '/controllers/UserdeshbordController.php';

$auth = new AuthController($conn);
$register = new RegisterController($conn);
$userController = new UserController($conn);
$registrationController = new RegistrationController($conn);
$eventController = new EventController($conn);
$reportController = new ReportController($conn);
$dashboardController = new DashboardController($conn);
$adminController = new AdminController($conn); 
$adminTasksController = new AdminTasksController($conn); 
$controller = new UserdeshbordController();



$action = $_GET['action'] ?? 'login';

switch($action){
    case 'login': 
        $auth->login(); 
        break;
    case 'register': 
        $register->register(); 
        break;
    case 'dashboard': 
        $dashboardController->index();  
        break;
    case 'logout': 
        $auth->logout(); 
        break;

    case 'settings':
        $adminController->settings();
        break;
    case 'updateProfile':
        $adminController->updateProfile();
        break;
    case 'changePassword':
        $adminController->changePassword();
        break;

   
    case 'manageUsers': 
        $userController->indexmanager(); 
        break;
    case 'add': 
        $userController->addUser(); 
        break;
    case 'edit': 
        $userController->editUser(); 
        break;
    case 'delete': 
        $userController->deleteUser(); 
        break;


    case 'manageRegistrations': 
        $registrationController->index(); 
        break;
    case 'registrationAction': 
        $registrationController->action(); 
        break;

    case 'manageEvents':
    case 'addEvent':
    case 'editEvent':
    case 'deleteEvent':
        $eventController->handleRequest();
        break;
    case 'reports':
        $reportController->index();
        break;
        
   case 'adminTasks': 
    $adminTasksController->admintask(); 
    break;

    case 'dashboardd': 
        $controller->dashboardd(); 
        break;

     case 'upcoming': 
        $controller->upcoming(); 
        break;  
        
        
     case 'myRegistrations': 
        $controller->myRegistrations();
        break;  

    default: 
        $auth->login(); 
        break;
}
