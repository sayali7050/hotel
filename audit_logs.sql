CREATE TABLE `audit_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT,
  `role` VARCHAR(20),
  `action` VARCHAR(100),
  `entity_type` VARCHAR(50),
  `entity_id` INT,
  `details` TEXT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
); 