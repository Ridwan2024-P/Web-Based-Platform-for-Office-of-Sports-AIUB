<?php

require_once 'models/Task.php';
require_once 'models/Announcement.php';

class VolunteerController {

    private $taskModel;
    private $announcementModel;

    public function __construct() {
        
        $this->taskModel = new Task();
        $this->announcementModel = new Announcement();
    }

    public function dashboardg() {
        $message = '';

        
        if(isset($_POST['update_status'])) {
            $task_id = $_POST['task_id'];
            $new_status = $_POST['status'];

            if($this->taskModel->updateStatus($task_id, $_SESSION['user_id'], $new_status)) {
                $message = "Task status updated successfully!";
            }
        }

        $tasks = $this->taskModel->getByVolunteer($_SESSION['user_id']);
        $announcements = $this->announcementModel->getAll();

      
        require 'views/volunteer/dashboard.php';
    }
}
