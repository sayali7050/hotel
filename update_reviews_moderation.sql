ALTER TABLE `reviews`
  ADD COLUMN `status` ENUM('pending','approved','rejected') DEFAULT 'pending' AFTER `created_at`,
  ADD COLUMN `admin_reply` TEXT NULL AFTER `status`; 