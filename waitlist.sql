CREATE TABLE `waitlist` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NULL,
  `guest_name` VARCHAR(100) NULL,
  `guest_email` VARCHAR(100) NULL,
  `guest_phone` VARCHAR(30) NULL,
  `room_type` VARCHAR(50) NOT NULL,
  `check_in_date` DATE NOT NULL,
  `check_out_date` DATE NOT NULL,
  `adults` INT NOT NULL,
  `children` INT NOT NULL,
  `special_requests` TEXT,
  `status` ENUM('waiting','notified','booked','cancelled') DEFAULT 'waiting',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
); 