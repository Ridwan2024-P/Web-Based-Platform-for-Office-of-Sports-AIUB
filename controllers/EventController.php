<?php
require_once __DIR__ . '/../models/Event.php';

class EventController {
    private $eventModel;

    public function __construct($db) {
        $this->eventModel = new Event($db);
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';

            switch($action) {
                case 'add':
                    $this->eventModel->add($_POST['name'], $_POST['date'], $_POST['venue'], $_POST['participants'], $_POST['status']);
                    break;
                case 'edit':
                    $this->eventModel->edit($_POST['id'], $_POST['name'], $_POST['date'], $_POST['venue'], $_POST['participants'], $_POST['status']);
                    break;
                case 'delete':
                    $this->eventModel->delete($_POST['id']);
                    break;
            }
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }

        $events = $this->eventModel->getAll();
        require __DIR__ . '/../views/Admin/ManageEvent.php';
    }
}
