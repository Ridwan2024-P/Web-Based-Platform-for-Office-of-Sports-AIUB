<?php
session_start();


$host = "localhost";
$user = "root";
$pass = "";
$db   = "rid";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
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
require_once __DIR__ . '/controllers/VolunteerController.php';


$auth = new AuthController($conn);
$register = new RegisterController($conn);
$userController = new UserController($conn);
$registrationController = new RegistrationController($conn);
$eventController = new EventController($conn);
$reportController = new ReportController($conn);
$dashboardController = new DashboardController($conn);
$adminController = new AdminController($conn); 
$adminTasksController = new AdminTasksController($conn); 
$userDashboardController = new UserdeshbordController();
$volunteerController = new VolunteerController($conn);


function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php?action=login");
        exit();
    }
}

function requireRole($roles) {
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


$action = $_GET['action'] ?? 'login';

if (isset($_SESSION['user_id'])) {
    $auth->checkTimeout();
}


switch($action) {

    
    case 'login':
        $auth->login();
        break;

    case 'register':
        $register->register();
        break;

    case 'logout':
        $auth->logout();
        break;

    case 'dashboard':
        requireLogin();
        requireRole('admin');
        $dashboardController->index();
        break;

    case 'settings':
    case 'updateProfile':
    case 'changePassword':
        requireLogin();
        requireRole('admin');
        $adminController->{$action}();
        break;

    case 'manageUsers':
        requireLogin();
        requireRole(['admin','volunteer']);
        $userController->indexmanager();
        break;

    case 'add':
        requireLogin();
        requireRole('admin');
        $userController->addUser();
        break;

    case 'edit':
        requireLogin();
        requireRole('admin');
        $userController->editUser();
        break;

    case 'delete':
        requireLogin();
        requireRole('admin');
        $userController->deleteUser();
        break;

    case 'manageRegistrations':
        requireLogin();
        requireRole(['admin','volunteer']);
        $registrationController->index();
        break;

    case 'registrationAction':
        requireLogin();
        requireRole('admin');
        $registrationController->action();
        break;

    case 'manageEvents':
    case 'addEvent':
    case 'editEvent':
    case 'deleteEvent':
        requireLogin();
        requireRole('admin');
        $eventController->handleRequest();
        break;

    case 'reports':
        requireLogin();
        requireRole('admin');
        $reportController->index();
        break;

    case 'adminTasks':
        requireLogin();
        requireRole('admin');
        $adminTasksController->admintask();
        break;


    case 'dashboardd':
        requireLogin();
        requireRole(['user','volunteer']);
        $userDashboardController->dashboardd();
        break;

    case 'upcoming':
        requireLogin();
        requireRole(['user','volunteer']);
        $userDashboardController->upcoming();
        break;

    case 'myRegistrations':
        requireLogin();
        requireRole(['user','volunteer']);
        $userDashboardController->myRegistrations();
        break;

 
    case 'volunteerDashboard':
        requireLogin();
        requireRole('volunteer');
        $volunteerController->dashboardg();
        break;

    default:
        $auth->login();
        break;
}
