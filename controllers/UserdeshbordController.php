<?php

require_once 'models/RegEvent.php';

class UserdeshbordController {
    private $eventModel;

    public function __construct() {
        $this->eventModel = new RegEvent();
    }

  
    public function dashboardd() {
        $message = null;

        if (isset($_POST['register_event'])) {
            $event_id = $_POST['event_id'];
            $user_id = $_SESSION['user_id'];

            $event = $this->eventModel->getEventName($event_id);
            $event_name = $event['name'];

            if ($this->eventModel->isAlreadyRegistered($user_id, $event_id)) {
                $message = "You are already registered for $event_name";
            } else {
                $this->eventModel->registerEvent($user_id, $event_id, $event_name);
                $message = "Successfully registered for $event_name";
            }
        }

        $events = $this->eventModel->getActiveEvents();
        $history = $this->eventModel->getHistory($_SESSION['user_id']);

        require 'views/user/UserDashboard.php';
    }

   
    public function upcoming() {
        $events = $this->eventModel->getActiveEvents();
        require 'views/user/upcomingEvents.php';
    }

   
    public function myRegistrations() {
        $history = $this->eventModel->getHistory($_SESSION['user_id']);
        require 'views/user/MyRegistrations.php';
    }
}
