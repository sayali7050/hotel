ALTER TABLE `bookings`
  ADD COLUMN `payment_status` VARCHAR(20) DEFAULT 'pending' AFTER `status`,
  ADD COLUMN `payment_method` VARCHAR(50) NULL AFTER `payment_status`,
  ADD COLUMN `payment_reference` VARCHAR(100) NULL AFTER `payment_method`,
  ADD COLUMN `payment_date` DATETIME NULL AFTER `payment_reference`,
  ADD COLUMN `refund_date` DATETIME NULL AFTER `payment_date`,
  ADD COLUMN `refund_amount` DECIMAL(10,2) NULL AFTER `refund_date`; 