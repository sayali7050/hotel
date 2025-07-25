<?php
/**
 * Apply Database Updates for Hotel Management System
 * 
 * This script applies the essential database updates needed for the improvements.
 * Run this file in your browser or via command line.
 */

// Prevent unauthorized access
if (!isset($_GET['apply']) || $_GET['apply'] !== 'updates') {
    die('Access denied. Add ?apply=updates to the URL to run this script.');
}

// Include CodeIgniter's database configuration
if (file_exists('application/config/database.php')) {
    require_once 'application/config/database.php';
} else {
    die('Database configuration file not found.');
}

// Create database connection
$mysqli = new mysqli(
    $db['default']['hostname'],
    $db['default']['username'],
    $db['default']['password'],
    $db['default']['database']
);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "<h2>Hotel Management System - Database Updates</h2>\n";
echo "<p>Starting database updates...</p>\n";

// Essential database updates
$updates = [
    // Add missing columns to users table
    "ALTER TABLE `users` ADD COLUMN `permissions` TEXT NULL AFTER `status`",
    "ALTER TABLE `users` ADD COLUMN `loyalty_points` INT DEFAULT 0 AFTER `permissions`",
    "ALTER TABLE `users` ADD COLUMN `last_2fa_code` VARCHAR(6) NULL AFTER `loyalty_points`",
    "ALTER TABLE `users` ADD COLUMN `last_2fa_expires` DATETIME NULL AFTER `last_2fa_code`",
    "ALTER TABLE `users` ADD COLUMN `preferences` TEXT NULL AFTER `last_2fa_expires`",
    "ALTER TABLE `users` ADD COLUMN `password_reset_token` VARCHAR(100) NULL AFTER `preferences`",
    "ALTER TABLE `users` ADD COLUMN `password_reset_expires` DATETIME NULL AFTER `password_reset_token`",
    "ALTER TABLE `users` ADD COLUMN `failed_login_attempts` INT DEFAULT 0 AFTER `password_reset_expires`",
    "ALTER TABLE `users` ADD COLUMN `last_failed_login` DATETIME NULL AFTER `failed_login_attempts`",
    "ALTER TABLE `users` ADD COLUMN `account_locked_until` DATETIME NULL AFTER `last_failed_login`",

    // Add missing columns to rooms table
    "ALTER TABLE `rooms` ADD COLUMN `cleaning_status` ENUM('clean','dirty','in_progress','out_of_service') DEFAULT 'clean' AFTER `status`",
    "ALTER TABLE `rooms` ADD COLUMN `maintenance_status` ENUM('ok','needs_attention','under_maintenance') DEFAULT 'ok' AFTER `cleaning_status`",
    "ALTER TABLE `rooms` ADD COLUMN `images` TEXT NULL AFTER `maintenance_status`",
    "ALTER TABLE `rooms` ADD COLUMN `floor_number` INT NULL AFTER `images`",
    "ALTER TABLE `rooms` ADD COLUMN `max_occupancy` INT NULL AFTER `floor_number`",

    // Add missing columns to bookings table
    "ALTER TABLE `bookings` ADD COLUMN `payment_status` ENUM('pending','paid','partial','failed','refunded') DEFAULT 'pending' AFTER `status`",
    "ALTER TABLE `bookings` ADD COLUMN `payment_reference` VARCHAR(100) NULL AFTER `payment_method`",
    "ALTER TABLE `bookings` ADD COLUMN `payment_date` DATETIME NULL AFTER `payment_reference`",
    "ALTER TABLE `bookings` ADD COLUMN `refund_date` DATETIME NULL AFTER `payment_date`",
    "ALTER TABLE `bookings` ADD COLUMN `refund_amount` DECIMAL(10,2) NULL AFTER `refund_date`",
    "ALTER TABLE `bookings` ADD COLUMN `coupon_code` VARCHAR(50) NULL AFTER `refund_amount`",
    "ALTER TABLE `bookings` ADD COLUMN `discount_amount` DECIMAL(10,2) DEFAULT 0 AFTER `coupon_code`",
    "ALTER TABLE `bookings` ADD COLUMN `booking_reference` VARCHAR(20) NULL AFTER `discount_amount`",
    "ALTER TABLE `bookings` ADD COLUMN `source` ENUM('website','phone','email','walk_in') DEFAULT 'website' AFTER `booking_reference`",

    // Update reviews table
    "ALTER TABLE `reviews` ADD COLUMN `status` ENUM('pending','approved','rejected') DEFAULT 'pending' AFTER `created_at`",
    "ALTER TABLE `reviews` ADD COLUMN `admin_reply` TEXT NULL AFTER `status`",
    "ALTER TABLE `reviews` ADD COLUMN `helpful_count` INT DEFAULT 0 AFTER `admin_reply`",

    // Create settings table
    "CREATE TABLE IF NOT EXISTS `settings` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `key` VARCHAR(100) NOT NULL UNIQUE,
      `value` TEXT NOT NULL,
      `description` VARCHAR(255) NULL,
      `type` ENUM('string','number','boolean','json') DEFAULT 'string',
      `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // Create email_templates table
    "CREATE TABLE IF NOT EXISTS `email_templates` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `name` VARCHAR(100) NOT NULL UNIQUE,
      `subject` VARCHAR(255) NOT NULL,
      `body` TEXT NOT NULL,
      `variables` TEXT NULL COMMENT 'JSON array of available variables',
      `active` TINYINT(1) DEFAULT 1,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // Create security_logs table
    "CREATE TABLE IF NOT EXISTS `security_logs` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `user_id` INT NULL,
      `ip_address` VARCHAR(45) NOT NULL,
      `user_agent` VARCHAR(500) NULL,
      `action` VARCHAR(100) NOT NULL,
      `details` TEXT NULL,
      `risk_level` ENUM('low','medium','high') DEFAULT 'low',
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      INDEX `user_id_idx` (`user_id`),
      INDEX `ip_address_idx` (`ip_address`),
      INDEX `action_idx` (`action`),
      INDEX `created_at_idx` (`created_at`),
      FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // Create loyalty_transactions table
    "CREATE TABLE IF NOT EXISTS `loyalty_transactions` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `user_id` INT NOT NULL,
      `booking_id` INT NULL,
      `transaction_type` ENUM('earned','redeemed','expired','adjusted') NOT NULL,
      `points` INT NOT NULL,
      `description` VARCHAR(255) NOT NULL,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      INDEX `user_id_idx` (`user_id`),
      INDEX `booking_id_idx` (`booking_id`),
      FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
      FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // Create communication_logs table  
    "CREATE TABLE IF NOT EXISTS `communication_logs` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `user_id` INT NULL,
      `email` VARCHAR(100) NOT NULL,
      `type` VARCHAR(50) NOT NULL,
      `subject` VARCHAR(255) NOT NULL,
      `body` TEXT NOT NULL,
      `status` ENUM('pending','sent','failed') DEFAULT 'pending',
      `sent_at` TIMESTAMP NULL,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      INDEX `user_id_idx` (`user_id`),
      INDEX `email_idx` (`email`),
      INDEX `type_idx` (`type`),
      INDEX `status_idx` (`status`),
      FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // Update existing bookings with references
    "UPDATE bookings SET booking_reference = CONCAT('BK', LPAD(id, 6, '0')) WHERE booking_reference IS NULL OR booking_reference = ''",

    // Insert default settings
    "INSERT IGNORE INTO `settings` (`key`, `value`, `description`, `type`) VALUES
     ('hotel_name', 'Grand Hotel', 'Hotel name displayed throughout the system', 'string'),
     ('hotel_email', 'info@grandhotel.com', 'Primary hotel email address', 'string'),
     ('hotel_phone', '+1-234-567-8900', 'Primary hotel phone number', 'string'),
     ('max_login_attempts', '5', 'Maximum failed login attempts before account lockout', 'number'),
     ('account_lockout_duration', '30', 'Account lockout duration in minutes', 'number'),
     ('loyalty_points_per_dollar', '1', 'Loyalty points earned per dollar spent', 'number'),
     ('loyalty_redemption_rate', '100', 'Points needed to redeem $1', 'number'),
     ('booking_cancellation_hours', '24', 'Hours before check-in when free cancellation is allowed', 'number'),
     ('enable_2fa', 'true', 'Enable two-factor authentication for admin users', 'boolean')",

    // Insert default email templates
    "INSERT IGNORE INTO `email_templates` (`name`, `subject`, `body`, `variables`) VALUES
     ('booking_confirmation', 'Booking Confirmation - {{booking_reference}}', 
      'Dear {{guest_name}},\n\nThank you for your booking!\n\nBooking Details:\nReference: {{booking_reference}}\nRoom: {{room_type}} - {{room_number}}\nCheck-in: {{check_in_date}}\nCheck-out: {{check_out_date}}\nTotal Amount: ${{total_amount}}\n\nWe look forward to welcoming you!\n\nBest regards,\nHotel Management',
      '[\"guest_name\",\"booking_reference\",\"room_type\",\"room_number\",\"check_in_date\",\"check_out_date\",\"total_amount\"]'),
     ('password_reset', 'Password Reset Request',
      'Dear {{user_name}},\n\nYou have requested a password reset. Click the link below to reset your password:\n\n{{reset_link}}\n\nThis link will expire in 1 hour.\n\nIf you did not request this, please ignore this email.\n\nBest regards,\nHotel Management',
      '[\"user_name\",\"reset_link\"]')"
];

$success_count = 0;
$error_count = 0;

echo "<ul>\n";

foreach ($updates as $update) {
    $short_query = substr(trim($update), 0, 60) . "...";
    
    if ($mysqli->query($update)) {
        $success_count++;
        echo "<li style='color: green;'>✓ {$short_query}</li>\n";
    } else {
        $error_count++;
        echo "<li style='color: red;'>✗ {$short_query}<br>Error: {$mysqli->error}</li>\n";
    }
    
    // Flush output for real-time updates
    if (ob_get_level()) ob_flush();
    flush();
}

echo "</ul>\n";

echo "<h3>Summary</h3>\n";
echo "<p><strong>Successful updates:</strong> {$success_count}</p>\n";
echo "<p><strong>Failed updates:</strong> {$error_count}</p>\n";

if ($error_count === 0) {
    echo "<p style='color: green; font-weight: bold;'>✓ All database updates completed successfully!</p>\n";
    echo "<h3>Next Steps:</h3>\n";
    echo "<ol>\n";
    echo "<li>Delete this file (apply_database_updates.php) for security</li>\n";
    echo "<li>Login to admin panel and test the new features</li>\n";
    echo "<li>Configure email settings in Admin > Settings</li>\n";
    echo "<li>Update hotel information</li>\n";
    echo "</ol>\n";
} else {
    echo "<p style='color: orange; font-weight: bold;'>⚠ Some updates failed. Please review the errors above.</p>\n";
    echo "<p>You may need to run the failed queries manually in your database.</p>\n";
}

$mysqli->close();

echo "<hr>\n";
echo "<p><small>Database updates completed at " . date('Y-m-d H:i:s') . "</small></p>\n";
?>