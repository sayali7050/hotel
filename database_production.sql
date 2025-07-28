-- Production-Ready Hotel Management Database Schema
-- Version: 2.0
-- Date: 2025-01-XX
-- Description: Secure, normalized database schema with proper constraints and indexes

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Create database
CREATE DATABASE IF NOT EXISTS `hotel_management` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `hotel_management`;

-- --------------------------------------------------------
-- Session table for CodeIgniter database sessions
-- --------------------------------------------------------

CREATE TABLE `ci_sessions` (
    `id` varchar(128) NOT NULL,
    `ip_address` varchar(45) NOT NULL,
    `timestamp` int(10) UNSIGNED DEFAULT 0 NOT NULL,
    `data` blob NOT NULL,
    PRIMARY KEY (`id`),
    KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Users table with enhanced security
-- --------------------------------------------------------

CREATE TABLE `users` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` varchar(50) NOT NULL,
    `email` varchar(100) NOT NULL,
    `password` varchar(255) NOT NULL,
    `first_name` varchar(50) NOT NULL,
    `last_name` varchar(50) NOT NULL,
    `phone` varchar(20) DEFAULT NULL,
    `address` text DEFAULT NULL,
    `role` enum('admin','staff','customer') NOT NULL DEFAULT 'customer',
    `status` enum('active','inactive','pending','locked') NOT NULL DEFAULT 'active',
    `email_verified` tinyint(1) DEFAULT 0,
    `email_verification_token` varchar(64) DEFAULT NULL,
    `password_reset_token` varchar(64) DEFAULT NULL,
    `password_reset_expires` datetime DEFAULT NULL,
    `last_login` datetime DEFAULT NULL,
    `login_attempts` int(3) DEFAULT 0,
    `locked_until` datetime DEFAULT NULL,
    `two_fa_secret` varchar(32) DEFAULT NULL,
    `two_fa_enabled` tinyint(1) DEFAULT 0,
    `last_2fa_code` varchar(6) DEFAULT NULL,
    `last_2fa_expires` datetime DEFAULT NULL,
    `permissions` json DEFAULT NULL,
    `preferences` json DEFAULT NULL,
    `loyalty_points` int(11) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `deleted_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_username_unique` (`username`),
    UNIQUE KEY `users_email_unique` (`email`),
    KEY `users_role_index` (`role`),
    KEY `users_status_index` (`status`),
    KEY `users_email_verified_index` (`email_verified`),
    KEY `users_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Rooms table with enhanced features
-- --------------------------------------------------------

CREATE TABLE `rooms` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `room_number` varchar(10) NOT NULL,
    `room_type` varchar(50) NOT NULL,
    `floor` int(3) DEFAULT NULL,
    `capacity` int(11) NOT NULL,
    `max_adults` int(3) NOT NULL DEFAULT 2,
    `max_children` int(3) NOT NULL DEFAULT 2,
    `price_per_night` decimal(10,2) NOT NULL,
    `description` text DEFAULT NULL,
    `amenities` json DEFAULT NULL,
    `images` json DEFAULT NULL,
    `status` enum('available','occupied','maintenance','reserved','out_of_order') DEFAULT 'available',
    `is_accessible` tinyint(1) DEFAULT 0,
    `smoking_allowed` tinyint(1) DEFAULT 0,
    `pets_allowed` tinyint(1) DEFAULT 0,
    `size_sqm` decimal(6,2) DEFAULT NULL,
    `bed_type` varchar(50) DEFAULT NULL,
    `view_type` varchar(50) DEFAULT NULL,
    `last_maintenance` date DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `deleted_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `rooms_room_number_unique` (`room_number`),
    KEY `rooms_room_type_index` (`room_type`),
    KEY `rooms_status_index` (`status`),
    KEY `rooms_capacity_index` (`capacity`),
    KEY `rooms_price_index` (`price_per_night`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Bookings table with comprehensive tracking
-- --------------------------------------------------------

CREATE TABLE `bookings` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `booking_reference` varchar(20) NOT NULL,
    `user_id` int(11) UNSIGNED NOT NULL,
    `room_id` int(11) UNSIGNED NOT NULL,
    `check_in_date` date NOT NULL,
    `check_out_date` date NOT NULL,
    `adults` int(11) NOT NULL DEFAULT 1,
    `children` int(11) NOT NULL DEFAULT 0,
    `rooms` int(11) NOT NULL DEFAULT 1,
    `nights` int(11) NOT NULL,
    `base_amount` decimal(10,2) NOT NULL,
    `tax_amount` decimal(10,2) DEFAULT 0.00,
    `discount_amount` decimal(10,2) DEFAULT 0.00,
    `total_amount` decimal(10,2) NOT NULL,
    `status` enum('pending','confirmed','checked_in','checked_out','cancelled','no_show') DEFAULT 'pending',
    `payment_status` enum('pending','partial','paid','refunded','failed') DEFAULT 'pending',
    `special_requests` text DEFAULT NULL,
    `guest_name` varchar(100) DEFAULT NULL,
    `guest_email` varchar(100) DEFAULT NULL,
    `guest_phone` varchar(20) DEFAULT NULL,
    `guest_address` text DEFAULT NULL,
    `payment_method` enum('credit_card','paypal','cash','debit_card','bank_transfer','online') DEFAULT 'credit_card',
    `coupon_code` varchar(50) DEFAULT NULL,
    `coupon_discount` decimal(10,2) DEFAULT 0.00,
    `loyalty_points_used` int(11) DEFAULT 0,
    `loyalty_points_earned` int(11) DEFAULT 0,
    `check_in_time` datetime DEFAULT NULL,
    `check_out_time` datetime DEFAULT NULL,
    `cancellation_reason` text DEFAULT NULL,
    `cancelled_by` int(11) UNSIGNED DEFAULT NULL,
    `cancelled_at` datetime DEFAULT NULL,
    `notes` text DEFAULT NULL,
    `created_by` int(11) UNSIGNED DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `deleted_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `bookings_reference_unique` (`booking_reference`),
    KEY `bookings_user_id_foreign` (`user_id`),
    KEY `bookings_room_id_foreign` (`room_id`),
    KEY `bookings_status_index` (`status`),
    KEY `bookings_payment_status_index` (`payment_status`),
    KEY `bookings_check_in_date_index` (`check_in_date`),
    KEY `bookings_check_out_date_index` (`check_out_date`),
    KEY `bookings_created_at_index` (`created_at`),
    KEY `bookings_cancelled_by_foreign` (`cancelled_by`),
    KEY `bookings_created_by_foreign` (`created_by`),
    CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `bookings_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
    CONSTRAINT `bookings_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `bookings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `bookings_dates_check` CHECK (`check_out_date` > `check_in_date`),
    CONSTRAINT `bookings_adults_check` CHECK (`adults` > 0),
    CONSTRAINT `bookings_rooms_check` CHECK (`rooms` > 0),
    CONSTRAINT `bookings_amounts_check` CHECK (`total_amount` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Payments table for transaction tracking
-- --------------------------------------------------------

CREATE TABLE `payments` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `payment_reference` varchar(50) NOT NULL,
    `booking_id` int(11) UNSIGNED NOT NULL,
    `amount` decimal(10,2) NOT NULL,
    `currency` char(3) DEFAULT 'USD',
    `payment_method` enum('cash','credit_card','debit_card','paypal','bank_transfer','online','crypto') NOT NULL,
    `payment_status` enum('pending','processing','completed','failed','cancelled','refunded','partial_refund') DEFAULT 'pending',
    `transaction_id` varchar(100) DEFAULT NULL,
    `gateway_response` json DEFAULT NULL,
    `gateway_reference` varchar(100) DEFAULT NULL,
    `payment_date` datetime DEFAULT NULL,
    `refund_amount` decimal(10,2) DEFAULT 0.00,
    `refund_date` datetime DEFAULT NULL,
    `refund_reason` text DEFAULT NULL,
    `processed_by` int(11) UNSIGNED DEFAULT NULL,
    `notes` text DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `payments_reference_unique` (`payment_reference`),
    KEY `payments_booking_id_foreign` (`booking_id`),
    KEY `payments_status_index` (`payment_status`),
    KEY `payments_method_index` (`payment_method`),
    KEY `payments_date_index` (`payment_date`),
    KEY `payments_processed_by_foreign` (`processed_by`),
    CONSTRAINT `payments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
    CONSTRAINT `payments_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `payments_amount_check` CHECK (`amount` > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Staff assignments table
-- --------------------------------------------------------

CREATE TABLE `staff_assignments` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `staff_id` int(11) UNSIGNED NOT NULL,
    `department` varchar(50) NOT NULL,
    `position` varchar(50) NOT NULL,
    `salary` decimal(10,2) DEFAULT NULL,
    `hire_date` date NOT NULL,
    `termination_date` date DEFAULT NULL,
    `status` enum('active','inactive','terminated','on_leave') DEFAULT 'active',
    `supervisor_id` int(11) UNSIGNED DEFAULT NULL,
    `shift_pattern` enum('day','night','rotating','flexible') DEFAULT 'day',
    `emergency_contact` varchar(100) DEFAULT NULL,
    `emergency_phone` varchar(20) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `staff_assignments_staff_unique` (`staff_id`),
    KEY `staff_assignments_department_index` (`department`),
    KEY `staff_assignments_status_index` (`status`),
    KEY `staff_assignments_supervisor_foreign` (`supervisor_id`),
    CONSTRAINT `staff_assignments_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `staff_assignments_supervisor_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Audit logs for security tracking
-- --------------------------------------------------------

CREATE TABLE `audit_logs` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` int(11) UNSIGNED DEFAULT NULL,
    `action` varchar(100) NOT NULL,
    `table_name` varchar(50) DEFAULT NULL,
    `record_id` int(11) UNSIGNED DEFAULT NULL,
    `old_values` json DEFAULT NULL,
    `new_values` json DEFAULT NULL,
    `ip_address` varchar(45) DEFAULT NULL,
    `user_agent` text DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `audit_logs_user_id_foreign` (`user_id`),
    KEY `audit_logs_action_index` (`action`),
    KEY `audit_logs_table_index` (`table_name`),
    KEY `audit_logs_created_at_index` (`created_at`),
    CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Rate limiting table
-- --------------------------------------------------------

CREATE TABLE `rate_limits` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `ip_address` varchar(45) NOT NULL,
    `action` varchar(50) NOT NULL,
    `attempts` int(11) DEFAULT 1,
    `last_attempt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `blocked_until` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `rate_limits_ip_action_unique` (`ip_address`, `action`),
    KEY `rate_limits_last_attempt_index` (`last_attempt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Coupons table for discounts
-- --------------------------------------------------------

CREATE TABLE `coupons` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` varchar(50) NOT NULL,
    `name` varchar(100) NOT NULL,
    `description` text DEFAULT NULL,
    `type` enum('percentage','fixed_amount','free_nights') NOT NULL,
    `value` decimal(10,2) NOT NULL,
    `minimum_amount` decimal(10,2) DEFAULT 0.00,
    `maximum_discount` decimal(10,2) DEFAULT NULL,
    `usage_limit` int(11) DEFAULT NULL,
    `used_count` int(11) DEFAULT 0,
    `per_user_limit` int(11) DEFAULT 1,
    `valid_from` date NOT NULL,
    `valid_until` date NOT NULL,
    `applicable_room_types` json DEFAULT NULL,
    `status` enum('active','inactive','expired') DEFAULT 'active',
    `created_by` int(11) UNSIGNED DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `coupons_code_unique` (`code`),
    KEY `coupons_status_index` (`status`),
    KEY `coupons_valid_dates_index` (`valid_from`, `valid_until`),
    KEY `coupons_created_by_foreign` (`created_by`),
    CONSTRAINT `coupons_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Used coupons tracking
-- --------------------------------------------------------

CREATE TABLE `used_coupons` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `coupon_id` int(11) UNSIGNED NOT NULL,
    `user_id` int(11) UNSIGNED NOT NULL,
    `booking_id` int(11) UNSIGNED NOT NULL,
    `discount_amount` decimal(10,2) NOT NULL,
    `used_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `used_coupons_coupon_id_foreign` (`coupon_id`),
    KEY `used_coupons_user_id_foreign` (`user_id`),
    KEY `used_coupons_booking_id_foreign` (`booking_id`),
    CONSTRAINT `used_coupons_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
    CONSTRAINT `used_coupons_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `used_coupons_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Reviews table
-- --------------------------------------------------------

CREATE TABLE `reviews` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `booking_id` int(11) UNSIGNED NOT NULL,
    `user_id` int(11) UNSIGNED NOT NULL,
    `room_id` int(11) UNSIGNED NOT NULL,
    `rating` tinyint(1) NOT NULL,
    `title` varchar(200) DEFAULT NULL,
    `comment` text DEFAULT NULL,
    `service_rating` tinyint(1) DEFAULT NULL,
    `cleanliness_rating` tinyint(1) DEFAULT NULL,
    `location_rating` tinyint(1) DEFAULT NULL,
    `value_rating` tinyint(1) DEFAULT NULL,
    `status` enum('pending','approved','rejected','flagged') DEFAULT 'pending',
    `moderated_by` int(11) UNSIGNED DEFAULT NULL,
    `moderated_at` datetime DEFAULT NULL,
    `moderation_notes` text DEFAULT NULL,
    `helpful_votes` int(11) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `reviews_booking_unique` (`booking_id`),
    KEY `reviews_user_id_foreign` (`user_id`),
    KEY `reviews_room_id_foreign` (`room_id`),
    KEY `reviews_rating_index` (`rating`),
    KEY `reviews_status_index` (`status`),
    KEY `reviews_moderated_by_foreign` (`moderated_by`),
    CONSTRAINT `reviews_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
    CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `reviews_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
    CONSTRAINT `reviews_moderated_by_foreign` FOREIGN KEY (`moderated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `reviews_rating_check` CHECK (`rating` >= 1 AND `rating` <= 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Waitlist table
-- --------------------------------------------------------

CREATE TABLE `waitlist` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` int(11) UNSIGNED DEFAULT NULL,
    `guest_email` varchar(100) NOT NULL,
    `guest_name` varchar(100) NOT NULL,
    `guest_phone` varchar(20) DEFAULT NULL,
    `check_in_date` date NOT NULL,
    `check_out_date` date NOT NULL,
    `adults` int(11) NOT NULL DEFAULT 1,
    `children` int(11) NOT NULL DEFAULT 0,
    `rooms` int(11) NOT NULL DEFAULT 1,
    `room_type_preference` varchar(50) DEFAULT NULL,
    `special_requests` text DEFAULT NULL,
    `status` enum('active','notified','converted','expired','cancelled') DEFAULT 'active',
    `priority` tinyint(1) DEFAULT 1,
    `notified_at` datetime DEFAULT NULL,
    `expires_at` datetime DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `waitlist_user_id_foreign` (`user_id`),
    KEY `waitlist_dates_index` (`check_in_date`, `check_out_date`),
    KEY `waitlist_status_index` (`status`),
    KEY `waitlist_priority_index` (`priority`),
    CONSTRAINT `waitlist_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Insert default admin user
-- --------------------------------------------------------

INSERT INTO `users` (
    `username`, `email`, `password`, `first_name`, `last_name`, 
    `role`, `status`, `email_verified`, `two_fa_enabled`,
    `permissions`, `created_at`
) VALUES (
    'admin', 
    'admin@hotel.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    'System', 
    'Administrator', 
    'admin', 
    'active', 
    1, 
    1,
    '{"manage_users": true, "manage_rooms": true, "manage_bookings": true, "view_reports": true, "manage_settings": true}',
    CURRENT_TIMESTAMP
);

-- --------------------------------------------------------
-- Sample room data
-- --------------------------------------------------------

INSERT INTO `rooms` (
    `room_number`, `room_type`, `floor`, `capacity`, `max_adults`, `max_children`,
    `price_per_night`, `description`, `amenities`, `status`, `bed_type`, `view_type`
) VALUES 
(
    '101', 'Standard', 1, 2, 2, 1, 89.99,
    'Comfortable standard room with modern amenities and city view.',
    '["WiFi", "Air Conditioning", "TV", "Private Bathroom", "Mini Fridge"]',
    'available', 'Queen Bed', 'City View'
),
(
    '102', 'Standard', 1, 2, 2, 1, 89.99,
    'Comfortable standard room with modern amenities and garden view.',
    '["WiFi", "Air Conditioning", "TV", "Private Bathroom", "Mini Fridge"]',
    'available', 'Queen Bed', 'Garden View'
),
(
    '201', 'Deluxe', 2, 3, 3, 2, 149.99,
    'Spacious deluxe room with premium amenities and ocean view.',
    '["WiFi", "Air Conditioning", "TV", "Private Bathroom", "Mini Bar", "Room Service", "Balcony"]',
    'available', 'King Bed', 'Ocean View'
),
(
    '301', 'Suite', 3, 4, 4, 2, 249.99,
    'Luxury suite with separate living area and premium services.',
    '["WiFi", "Air Conditioning", "TV", "Private Bathroom", "Mini Bar", "Room Service", "Jacuzzi", "Butler Service"]',
    'available', 'King Bed + Sofa Bed', 'Ocean View'
);

-- --------------------------------------------------------
-- Create indexes for performance
-- --------------------------------------------------------

-- Additional composite indexes for common queries
CREATE INDEX `bookings_user_dates_idx` ON `bookings` (`user_id`, `check_in_date`, `check_out_date`);
CREATE INDEX `bookings_room_dates_idx` ON `bookings` (`room_id`, `check_in_date`, `check_out_date`);
CREATE INDEX `bookings_status_dates_idx` ON `bookings` (`status`, `check_in_date`, `check_out_date`);
CREATE INDEX `payments_booking_status_idx` ON `payments` (`booking_id`, `payment_status`);
CREATE INDEX `audit_logs_user_action_idx` ON `audit_logs` (`user_id`, `action`, `created_at`);

-- --------------------------------------------------------
-- Create triggers for audit logging
-- --------------------------------------------------------

DELIMITER $$

CREATE TRIGGER `users_audit_insert` AFTER INSERT ON `users`
FOR EACH ROW BEGIN
    INSERT INTO `audit_logs` (`user_id`, `action`, `table_name`, `record_id`, `new_values`, `ip_address`)
    VALUES (NEW.id, 'INSERT', 'users', NEW.id, JSON_OBJECT('username', NEW.username, 'email', NEW.email, 'role', NEW.role), @user_ip);
END$$

CREATE TRIGGER `users_audit_update` AFTER UPDATE ON `users`
FOR EACH ROW BEGIN
    INSERT INTO `audit_logs` (`user_id`, `action`, `table_name`, `record_id`, `old_values`, `new_values`, `ip_address`)
    VALUES (NEW.id, 'UPDATE', 'users', NEW.id, 
        JSON_OBJECT('username', OLD.username, 'email', OLD.email, 'role', OLD.role, 'status', OLD.status),
        JSON_OBJECT('username', NEW.username, 'email', NEW.email, 'role', NEW.role, 'status', NEW.status),
        @user_ip);
END$$

CREATE TRIGGER `bookings_audit_insert` AFTER INSERT ON `bookings`
FOR EACH ROW BEGIN
    INSERT INTO `audit_logs` (`user_id`, `action`, `table_name`, `record_id`, `new_values`, `ip_address`)
    VALUES (NEW.user_id, 'INSERT', 'bookings', NEW.id, 
        JSON_OBJECT('booking_reference', NEW.booking_reference, 'room_id', NEW.room_id, 'total_amount', NEW.total_amount, 'status', NEW.status),
        @user_ip);
END$$

CREATE TRIGGER `bookings_audit_update` AFTER UPDATE ON `bookings`
FOR EACH ROW BEGIN
    INSERT INTO `audit_logs` (`user_id`, `action`, `table_name`, `record_id`, `old_values`, `new_values`, `ip_address`)
    VALUES (NEW.user_id, 'UPDATE', 'bookings', NEW.id,
        JSON_OBJECT('status', OLD.status, 'payment_status', OLD.payment_status),
        JSON_OBJECT('status', NEW.status, 'payment_status', NEW.payment_status),
        @user_ip);
END$$

DELIMITER ;

-- Set auto increment starting values
ALTER TABLE `users` AUTO_INCREMENT = 1000;
ALTER TABLE `rooms` AUTO_INCREMENT = 100;
ALTER TABLE `bookings` AUTO_INCREMENT = 10000;
ALTER TABLE `payments` AUTO_INCREMENT = 50000;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;