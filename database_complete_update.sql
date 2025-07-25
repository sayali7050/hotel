-- Complete Database Schema Update for Hotel Management System
-- Run this script to update your existing database with all missing features

-- Add missing columns to users table
ALTER TABLE `users` 
ADD COLUMN `permissions` TEXT NULL AFTER `status`,
ADD COLUMN `loyalty_points` INT DEFAULT 0 AFTER `permissions`,
ADD COLUMN `last_2fa_code` VARCHAR(6) NULL AFTER `loyalty_points`,
ADD COLUMN `last_2fa_expires` DATETIME NULL AFTER `last_2fa_code`,
ADD COLUMN `preferences` TEXT NULL AFTER `last_2fa_expires`,
ADD COLUMN `password_reset_token` VARCHAR(100) NULL AFTER `preferences`,
ADD COLUMN `password_reset_expires` DATETIME NULL AFTER `password_reset_token`,
ADD COLUMN `failed_login_attempts` INT DEFAULT 0 AFTER `password_reset_expires`,
ADD COLUMN `last_failed_login` DATETIME NULL AFTER `failed_login_attempts`,
ADD COLUMN `account_locked_until` DATETIME NULL AFTER `last_failed_login`;

-- Add missing columns to rooms table
ALTER TABLE `rooms`
ADD COLUMN `cleaning_status` ENUM('clean','dirty','in_progress','out_of_service') DEFAULT 'clean' AFTER `status`,
ADD COLUMN `maintenance_status` ENUM('ok','needs_attention','under_maintenance') DEFAULT 'ok' AFTER `cleaning_status`,
ADD COLUMN `images` TEXT NULL AFTER `maintenance_status`,
ADD COLUMN `floor_number` INT NULL AFTER `images`,
ADD COLUMN `max_occupancy` INT NULL AFTER `floor_number`;

-- Add missing columns to bookings table
ALTER TABLE `bookings`
ADD COLUMN `payment_status` ENUM('pending','paid','partial','failed','refunded') DEFAULT 'pending' AFTER `status`,
ADD COLUMN `payment_reference` VARCHAR(100) NULL AFTER `payment_method`,
ADD COLUMN `payment_date` DATETIME NULL AFTER `payment_reference`,
ADD COLUMN `refund_date` DATETIME NULL AFTER `payment_date`,
ADD COLUMN `refund_amount` DECIMAL(10,2) NULL AFTER `refund_date`,
ADD COLUMN `coupon_code` VARCHAR(50) NULL AFTER `refund_amount`,
ADD COLUMN `discount_amount` DECIMAL(10,2) DEFAULT 0 AFTER `coupon_code`,
ADD COLUMN `booking_reference` VARCHAR(20) NULL AFTER `discount_amount`,
ADD COLUMN `source` ENUM('website','phone','email','walk_in') DEFAULT 'website' AFTER `booking_reference`;

-- Update reviews table with moderation
ALTER TABLE `reviews`
ADD COLUMN `status` ENUM('pending','approved','rejected') DEFAULT 'pending' AFTER `created_at`,
ADD COLUMN `admin_reply` TEXT NULL AFTER `status`,
ADD COLUMN `helpful_count` INT DEFAULT 0 AFTER `admin_reply`;

-- Create room_inventory table
CREATE TABLE IF NOT EXISTS `room_inventory` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `room_type` VARCHAR(50) NOT NULL,
  `date` DATE NOT NULL,
  `total_rooms` INT NOT NULL,
  `booked_rooms` INT NOT NULL DEFAULT 0,
  `blocked_rooms` INT NOT NULL DEFAULT 0,
  `available_rooms` INT GENERATED ALWAYS AS (total_rooms - booked_rooms - blocked_rooms) STORED,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `room_type_date` (`room_type`, `date`),
  INDEX `date_idx` (`date`),
  INDEX `room_type_idx` (`room_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create rate_plans table
CREATE TABLE IF NOT EXISTS `rate_plans` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `room_type` VARCHAR(50) NOT NULL,
  `plan_name` VARCHAR(100) NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `price_per_night` DECIMAL(10,2) NOT NULL,
  `min_stay` INT DEFAULT 1,
  `max_stay` INT DEFAULT NULL,
  `promotion_name` VARCHAR(100) NULL,
  `promotion_description` TEXT NULL,
  `active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `room_type_idx` (`room_type`),
  INDEX `date_range_idx` (`start_date`, `end_date`),
  INDEX `active_idx` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create communication_logs table
CREATE TABLE IF NOT EXISTS `communication_logs` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create maintenance_requests table
CREATE TABLE IF NOT EXISTS `maintenance_requests` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `room_id` INT NOT NULL,
  `reported_by` INT NOT NULL,
  `assigned_to` INT NULL,
  `priority` ENUM('low','medium','high','urgent') DEFAULT 'medium',
  `category` VARCHAR(50) NOT NULL,
  `issue` TEXT NOT NULL,
  `resolution` TEXT NULL,
  `status` ENUM('open','in_progress','resolved','cancelled') DEFAULT 'open',
  `estimated_completion` DATETIME NULL,
  `actual_completion` DATETIME NULL,
  `cost` DECIMAL(10,2) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `room_id_idx` (`room_id`),
  INDEX `status_idx` (`status`),
  INDEX `priority_idx` (`priority`),
  FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`reported_by`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create loyalty_transactions table
CREATE TABLE IF NOT EXISTS `loyalty_transactions` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create email_templates table
CREATE TABLE IF NOT EXISTS `email_templates` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL UNIQUE,
  `subject` VARCHAR(255) NOT NULL,
  `body` TEXT NOT NULL,
  `variables` TEXT NULL COMMENT 'JSON array of available variables',
  `active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create settings table for system configuration
CREATE TABLE IF NOT EXISTS `settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `key` VARCHAR(100) NOT NULL UNIQUE,
  `value` TEXT NOT NULL,
  `description` VARCHAR(255) NULL,
  `type` ENUM('string','number','boolean','json') DEFAULT 'string',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create security_logs table
CREATE TABLE IF NOT EXISTS `security_logs` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default email templates
INSERT INTO `email_templates` (`name`, `subject`, `body`, `variables`) VALUES
('booking_confirmation', 'Booking Confirmation - {{booking_reference}}', 
'Dear {{guest_name}},\n\nThank you for your booking!\n\nBooking Details:\nReference: {{booking_reference}}\nRoom: {{room_type}} - {{room_number}}\nCheck-in: {{check_in_date}}\nCheck-out: {{check_out_date}}\nTotal Amount: ${{total_amount}}\n\nWe look forward to welcoming you!\n\nBest regards,\nHotel Management',
'["guest_name","booking_reference","room_type","room_number","check_in_date","check_out_date","total_amount"]'),

('booking_cancellation', 'Booking Cancellation - {{booking_reference}}',
'Dear {{guest_name}},\n\nYour booking has been cancelled.\n\nBooking Reference: {{booking_reference}}\nCancellation Date: {{cancellation_date}}\nRefund Amount: ${{refund_amount}}\n\nRefund will be processed within 3-5 business days.\n\nBest regards,\nHotel Management',
'["guest_name","booking_reference","cancellation_date","refund_amount"]'),

('password_reset', 'Password Reset Request',
'Dear {{user_name}},\n\nYou have requested a password reset. Click the link below to reset your password:\n\n{{reset_link}}\n\nThis link will expire in 1 hour.\n\nIf you did not request this, please ignore this email.\n\nBest regards,\nHotel Management',
'["user_name","reset_link"]'),

('2fa_code', 'Your 2FA Authentication Code',
'Dear {{user_name}},\n\nYour 2FA authentication code is: {{code}}\n\nThis code will expire in 5 minutes.\n\nBest regards,\nHotel Management',
'["user_name","code"]');

-- Insert default system settings
INSERT INTO `settings` (`key`, `value`, `description`, `type`) VALUES
('hotel_name', 'Grand Hotel', 'Hotel name displayed throughout the system', 'string'),
('hotel_email', 'info@grandhotel.com', 'Primary hotel email address', 'string'),
('hotel_phone', '+1-234-567-8900', 'Primary hotel phone number', 'string'),
('max_login_attempts', '5', 'Maximum failed login attempts before account lockout', 'number'),
('account_lockout_duration', '30', 'Account lockout duration in minutes', 'number'),
('loyalty_points_per_dollar', '1', 'Loyalty points earned per dollar spent', 'number'),
('loyalty_redemption_rate', '100', 'Points needed to redeem $1', 'number'),
('booking_cancellation_hours', '24', 'Hours before check-in when free cancellation is allowed', 'number'),
('email_smtp_host', '', 'SMTP server hostname', 'string'),
('email_smtp_port', '587', 'SMTP server port', 'number'),
('email_smtp_username', '', 'SMTP username', 'string'),
('email_smtp_password', '', 'SMTP password', 'string'),
('enable_2fa', 'true', 'Enable two-factor authentication for admin users', 'boolean');

-- Initialize room inventory for existing rooms (next 365 days)
INSERT INTO `room_inventory` (`room_type`, `date`, `total_rooms`)
SELECT 
  r.room_type,
  DATE_ADD(CURDATE(), INTERVAL n.n DAY) as date,
  COUNT(*) as total_rooms
FROM rooms r
CROSS JOIN (
  SELECT 0 as n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION
  SELECT 10 UNION SELECT 11 UNION SELECT 12 UNION SELECT 13 UNION SELECT 14 UNION SELECT 15 UNION SELECT 16 UNION SELECT 17 UNION SELECT 18 UNION SELECT 19 UNION
  SELECT 20 UNION SELECT 21 UNION SELECT 22 UNION SELECT 23 UNION SELECT 24 UNION SELECT 25 UNION SELECT 26 UNION SELECT 27 UNION SELECT 28 UNION SELECT 29 UNION
  SELECT 30 UNION SELECT 31 UNION SELECT 32 UNION SELECT 33 UNION SELECT 34 UNION SELECT 35 UNION SELECT 36 UNION SELECT 37 UNION SELECT 38 UNION SELECT 39 UNION
  SELECT 40 UNION SELECT 41 UNION SELECT 42 UNION SELECT 43 UNION SELECT 44 UNION SELECT 45 UNION SELECT 46 UNION SELECT 47 UNION SELECT 48 UNION SELECT 49 UNION
  SELECT 50 UNION SELECT 51 UNION SELECT 52 UNION SELECT 53 UNION SELECT 54 UNION SELECT 55 UNION SELECT 56 UNION SELECT 57 UNION SELECT 58 UNION SELECT 59 UNION
  SELECT 60 UNION SELECT 61 UNION SELECT 62 UNION SELECT 63 UNION SELECT 64 UNION SELECT 65 UNION SELECT 66 UNION SELECT 67 UNION SELECT 68 UNION SELECT 69 UNION
  SELECT 70 UNION SELECT 71 UNION SELECT 72 UNION SELECT 73 UNION SELECT 74 UNION SELECT 75 UNION SELECT 76 UNION SELECT 77 UNION SELECT 78 UNION SELECT 79 UNION
  SELECT 80 UNION SELECT 81 UNION SELECT 82 UNION SELECT 83 UNION SELECT 84 UNION SELECT 85 UNION SELECT 86 UNION SELECT 87 UNION SELECT 88 UNION SELECT 89 UNION
  SELECT 90 UNION SELECT 91 UNION SELECT 92 UNION SELECT 93 UNION SELECT 94 UNION SELECT 95 UNION SELECT 96 UNION SELECT 97 UNION SELECT 98 UNION SELECT 99 UNION
  SELECT 100 UNION SELECT 101 UNION SELECT 102 UNION SELECT 103 UNION SELECT 104 UNION SELECT 105 UNION SELECT 106 UNION SELECT 107 UNION SELECT 108 UNION SELECT 109 UNION
  SELECT 110 UNION SELECT 111 UNION SELECT 112 UNION SELECT 113 UNION SELECT 114 UNION SELECT 115 UNION SELECT 116 UNION SELECT 117 UNION SELECT 118 UNION SELECT 119 UNION
  SELECT 120 UNION SELECT 121 UNION SELECT 122 UNION SELECT 123 UNION SELECT 124 UNION SELECT 125 UNION SELECT 126 UNION SELECT 127 UNION SELECT 128 UNION SELECT 129 UNION
  SELECT 130 UNION SELECT 131 UNION SELECT 132 UNION SELECT 133 UNION SELECT 134 UNION SELECT 135 UNION SELECT 136 UNION SELECT 137 UNION SELECT 138 UNION SELECT 139 UNION
  SELECT 140 UNION SELECT 141 UNION SELECT 142 UNION SELECT 143 UNION SELECT 144 UNION SELECT 145 UNION SELECT 146 UNION SELECT 147 UNION SELECT 148 UNION SELECT 149 UNION
  SELECT 150 UNION SELECT 151 UNION SELECT 152 UNION SELECT 153 UNION SELECT 154 UNION SELECT 155 UNION SELECT 156 UNION SELECT 157 UNION SELECT 158 UNION SELECT 159 UNION
  SELECT 160 UNION SELECT 161 UNION SELECT 162 UNION SELECT 163 UNION SELECT 164 UNION SELECT 165 UNION SELECT 166 UNION SELECT 167 UNION SELECT 168 UNION SELECT 169 UNION
  SELECT 170 UNION SELECT 171 UNION SELECT 172 UNION SELECT 173 UNION SELECT 174 UNION SELECT 175 UNION SELECT 176 UNION SELECT 177 UNION SELECT 178 UNION SELECT 179 UNION
  SELECT 180 UNION SELECT 181 UNION SELECT 182 UNION SELECT 183 UNION SELECT 184 UNION SELECT 185 UNION SELECT 186 UNION SELECT 187 UNION SELECT 188 UNION SELECT 189 UNION
  SELECT 190 UNION SELECT 191 UNION SELECT 192 UNION SELECT 193 UNION SELECT 194 UNION SELECT 195 UNION SELECT 196 UNION SELECT 197 UNION SELECT 198 UNION SELECT 199 UNION
  SELECT 200 UNION SELECT 201 UNION SELECT 202 UNION SELECT 203 UNION SELECT 204 UNION SELECT 205 UNION SELECT 206 UNION SELECT 207 UNION SELECT 208 UNION SELECT 209 UNION
  SELECT 210 UNION SELECT 211 UNION SELECT 212 UNION SELECT 213 UNION SELECT 214 UNION SELECT 215 UNION SELECT 216 UNION SELECT 217 UNION SELECT 218 UNION SELECT 219 UNION
  SELECT 220 UNION SELECT 221 UNION SELECT 222 UNION SELECT 223 UNION SELECT 224 UNION SELECT 225 UNION SELECT 226 UNION SELECT 227 UNION SELECT 228 UNION SELECT 229 UNION
  SELECT 230 UNION SELECT 231 UNION SELECT 232 UNION SELECT 233 UNION SELECT 234 UNION SELECT 235 UNION SELECT 236 UNION SELECT 237 UNION SELECT 238 UNION SELECT 239 UNION
  SELECT 240 UNION SELECT 241 UNION SELECT 242 UNION SELECT 243 UNION SELECT 244 UNION SELECT 245 UNION SELECT 246 UNION SELECT 247 UNION SELECT 248 UNION SELECT 249 UNION
  SELECT 250 UNION SELECT 251 UNION SELECT 252 UNION SELECT 253 UNION SELECT 254 UNION SELECT 255 UNION SELECT 256 UNION SELECT 257 UNION SELECT 258 UNION SELECT 259 UNION
  SELECT 260 UNION SELECT 261 UNION SELECT 262 UNION SELECT 263 UNION SELECT 264 UNION SELECT 265 UNION SELECT 266 UNION SELECT 267 UNION SELECT 268 UNION SELECT 269 UNION
  SELECT 270 UNION SELECT 271 UNION SELECT 272 UNION SELECT 273 UNION SELECT 274 UNION SELECT 275 UNION SELECT 276 UNION SELECT 277 UNION SELECT 278 UNION SELECT 279 UNION
  SELECT 280 UNION SELECT 281 UNION SELECT 282 UNION SELECT 283 UNION SELECT 284 UNION SELECT 285 UNION SELECT 286 UNION SELECT 287 UNION SELECT 288 UNION SELECT 289 UNION
  SELECT 290 UNION SELECT 291 UNION SELECT 292 UNION SELECT 293 UNION SELECT 294 UNION SELECT 295 UNION SELECT 296 UNION SELECT 297 UNION SELECT 298 UNION SELECT 299 UNION
  SELECT 300 UNION SELECT 301 UNION SELECT 302 UNION SELECT 303 UNION SELECT 304 UNION SELECT 305 UNION SELECT 306 UNION SELECT 307 UNION SELECT 308 UNION SELECT 309 UNION
  SELECT 310 UNION SELECT 311 UNION SELECT 312 UNION SELECT 313 UNION SELECT 314 UNION SELECT 315 UNION SELECT 316 UNION SELECT 317 UNION SELECT 318 UNION SELECT 319 UNION
  SELECT 320 UNION SELECT 321 UNION SELECT 322 UNION SELECT 323 UNION SELECT 324 UNION SELECT 325 UNION SELECT 326 UNION SELECT 327 UNION SELECT 328 UNION SELECT 329 UNION
  SELECT 330 UNION SELECT 331 UNION SELECT 332 UNION SELECT 333 UNION SELECT 334 UNION SELECT 335 UNION SELECT 336 UNION SELECT 337 UNION SELECT 338 UNION SELECT 339 UNION
  SELECT 340 UNION SELECT 341 UNION SELECT 342 UNION SELECT 343 UNION SELECT 344 UNION SELECT 345 UNION SELECT 346 UNION SELECT 347 UNION SELECT 348 UNION SELECT 349 UNION
  SELECT 350 UNION SELECT 351 UNION SELECT 352 UNION SELECT 353 UNION SELECT 354 UNION SELECT 355 UNION SELECT 356 UNION SELECT 357 UNION SELECT 358 UNION SELECT 359 UNION
  SELECT 360 UNION SELECT 361 UNION SELECT 362 UNION SELECT 363 UNION SELECT 364
) n
WHERE r.status = 'available'
GROUP BY r.room_type, DATE_ADD(CURDATE(), INTERVAL n.n DAY)
ON DUPLICATE KEY UPDATE total_rooms = VALUES(total_rooms);

-- Update existing bookings to set booking references
UPDATE bookings SET booking_reference = CONCAT('BK', LPAD(id, 6, '0')) WHERE booking_reference IS NULL;

-- Create indexes for better performance
CREATE INDEX idx_bookings_dates ON bookings(check_in_date, check_out_date);
CREATE INDEX idx_bookings_status ON bookings(status);
CREATE INDEX idx_bookings_payment_status ON bookings(payment_status);
CREATE INDEX idx_rooms_type_status ON rooms(room_type, status);
CREATE INDEX idx_users_role_status ON users(role, status);

COMMIT;