must be use php 7 up version;
Use mysql(i) database;
create database name is rid;
Run the Sql quarry;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user','volunteer') DEFAULT 'user',
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `venue` varchar(255) NOT NULL,
  `participants` int(11) DEFAULT 0,
  `status` enum('Active','Inactive','Completed','Cancelled') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `event_name` varchar(150) NOT NULL,
  `registration_date` date DEFAULT curdate(),
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_registration` (`user_id`,`event_id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `registrations_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE `volunteer_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `volunteer_id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `task_date` date NOT NULL,
  `status` enum('Pending','In Progress','Completed') DEFAULT 'Pending',
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_volunteer` FOREIGN KEY (`volunteer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `announcements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` varchar(255) NOT NULL,
  `event_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
