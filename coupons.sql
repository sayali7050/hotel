CREATE TABLE `coupons` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `code` VARCHAR(50) NOT NULL UNIQUE,
  `discount_type` ENUM('percent','fixed') NOT NULL,
  `discount_value` DECIMAL(10,2) NOT NULL,
  `max_uses` INT DEFAULT NULL,
  `used_count` INT DEFAULT 0,
  `expiry_date` DATE DEFAULT NULL,
  `active` TINYINT(1) DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
); 