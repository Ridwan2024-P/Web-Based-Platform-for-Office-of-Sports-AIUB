<?php
require_once 'controllers/AuthController.php';
require_once 'controllers/RegisterController.php';

$auth = new AuthController();
$register = new RegisterController();

$action = isset($_GET['action']) ? $_GET['action'] : 'login';

switch($action){
    case 'login':
        $auth->login();
        break;

    case 'register':
        $register->register();
        break;

    case 'dashboard':
        $auth->dashboard();
        break;

    case 'logout':
        $auth->logout();
        break;

    default:
        $auth->login();
        break;
}
?>
