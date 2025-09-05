<?php
require_once 'controllers/AuthController.php';
$auth = new AuthController();

$action = isset($_GET['action']) ? $_GET['action'] : 'login';

switch($action){
    case 'login': $auth->login(); break;
    case 'dashboard': $auth->dashboard(); break;
    case 'logout': $auth->logout(); break;
    default: $auth->login(); break;
}
