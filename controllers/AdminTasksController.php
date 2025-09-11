<?php

require_once __DIR__ . '/../models/Model.php';

class AdminTasksController {
    private $model;
    private $message = '';

   public function __construct($conn) {
        $this->model = new Model($conn);
    }

    public function admintask() {
        $this->handleAddTask();
        $this->handleAddAnnouncement();

        $volunteers = $this->model->getVolunteers();
        $events = $this->model->getActiveEvents();
        $message = $this->message;

        require_once __DIR__ . '/../views/Admin/admin_tasks_view.php';
    }

    private function handleAddTask() {
        if(isset($_POST['add_task'])){
            $volunteer_id = $_POST['volunteer_id'];
            $event_id = $_POST['event_id'];
            $task_name = $_POST['task_name'];
            $task_date = $_POST['task_date'];

            if($this->model->addTask($volunteer_id, $event_id, $task_name, $task_date)){
                $this->message = "Task added successfully!";
            } else {
                $this->message = "Failed to add task!";
            }
        }
    }

    private function handleAddAnnouncement() {
        if(isset($_POST['add_announcement'])){
            $announcement = $_POST['announcement'];
            $event_date = $_POST['event_date'] ?: null;

            if($this->model->addAnnouncement($announcement, $event_date)){
                $this->message = "Announcement added successfully!";
            } else {
                $this->message = "Failed to add announcement!";
            }
        }
    }
}
