-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 20, 2025 at 07:55 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hotel`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `adults` int(11) NOT NULL DEFAULT 1,
  `children` int(11) NOT NULL DEFAULT 0,
  `rooms` int(11) NOT NULL DEFAULT 1,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','checked_in','checked_out','cancelled') DEFAULT 'pending',
  `special_requests` text DEFAULT NULL,
  `guest_name` varchar(100) DEFAULT NULL,
  `guest_email` varchar(100) DEFAULT NULL,
  `guest_phone` varchar(20) DEFAULT NULL,
  `guest_address` text DEFAULT NULL,
  `payment_method` enum('credit_card','paypal','cash','debit_card','online') DEFAULT 'credit_card',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `room_id`, `check_in_date`, `check_out_date`, `adults`, `children`, `rooms`, `total_amount`, `status`, `special_requests`, `guest_name`, `guest_email`, `guest_phone`, `guest_address`, `payment_method`, `created_at`, `updated_at`) VALUES
(1, 6, 6, '2025-07-21', '2025-07-26', 1, 0, 1, '22835.00', 'checked_in', '', NULL, NULL, NULL, NULL, 'credit_card', '2025-07-19 17:13:34', '2025-07-19 17:44:29');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','credit_card','debit_card','online') NOT NULL,
  `payment_status` enum('pending','completed','failed','refunded') DEFAULT 'pending',
  `transaction_id` varchar(100) DEFAULT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_number` varchar(10) NOT NULL,
  `room_type` varchar(50) NOT NULL,
  `capacity` int(11) NOT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `amenities` text DEFAULT NULL,
  `status` enum('available','occupied','maintenance','reserved') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_number`, `room_type`, `capacity`, `price_per_night`, `description`, `amenities`, `status`, `created_at`, `updated_at`) VALUES
(1, '101', 'Standard', 1, '80.00', 'Comfortable single room with basic amenities', 'WiFi, TV, AC, Private Bathroom', 'available', '2025-07-19 04:56:47', '2025-07-19 16:44:36'),
(2, '102', 'Standard Double', 2, '120.00', 'Spacious double room with city view', 'WiFi, TV, AC, Private Bathroom, Mini Fridge', 'available', '2025-07-19 04:56:47', '2025-07-19 04:56:47'),
(3, '201', 'Suite', 4, '200.00', 'Luxury suite with separate living area', 'WiFi, TV, AC, Private Bathroom, Mini Bar, Room Service', 'reserved', '2025-07-19 04:56:47', '2025-07-19 16:43:22'),
(4, '202', 'Executive', 2, '180.00', 'Executive suite with business amenities', 'WiFi, TV, AC, Private Bathroom, Work Desk, Coffee Maker', 'occupied', '2025-07-19 04:56:47', '2025-07-19 16:43:09'),
(5, '301', 'Executive', 6, '500.00', 'Ultimate luxury with premium services', 'WiFi, TV, AC, Private Bathroom, Jacuzzi, Butler Service, Premium View', 'maintenance', '2025-07-19 04:56:47', '2025-07-19 16:43:35'),
(6, '22', 'Family', 3, '4567.00', 'fdgfgb gvb', ' ', 'available', '2025-07-19 16:44:09', '2025-07-19 16:44:09');

-- --------------------------------------------------------

--
-- Table structure for table `staff_assignments`
--

CREATE TABLE `staff_assignments` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `department` varchar(50) NOT NULL,
  `position` varchar(50) NOT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `hire_date` date NOT NULL,
  `status` enum('active','inactive','terminated') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_assignments`
--

INSERT INTO `staff_assignments` (`id`, `staff_id`, `department`, `position`, `salary`, `hire_date`, `status`, `created_at`, `updated_at`) VALUES
(2, 3, 'Housekeeping', 'Supervisor', '2800.00', '2024-02-01', 'active', '2025-07-19 04:56:47', '2025-07-19 04:56:47'),
(3, 5, 'Management', 'manager', '5896.00', '2025-07-15', 'active', '2025-07-19 16:23:17', '2025-07-19 16:23:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('admin','staff','customer') NOT NULL DEFAULT 'customer',
  `status` enum('active','inactive','pending') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `first_name`, `last_name`, `phone`, `address`, `role`, `status`, `created_at`, `updated_at`) VALUES
(3, 'staff2', 'staff2@hotel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jane', 'Smith', '+1234567891', NULL, 'staff', 'active', '2025-07-19 04:56:47', '2025-07-19 04:56:47'),
(4, 'admin', 'admin@hotel.com', '$2y$10$gxbYlQ3Ww2dpzLJcMWaL6eXzpwAZ143JvUnC1U.4m4mjI7fWV4nk6', 'System', 'Administrator', NULL, NULL, 'admin', 'active', '2025-07-19 14:25:57', '2025-07-19 15:18:29'),
(5, 'amol', 'amol@gmail.com', '$2y$10$VL/YwZeIFgFnws.q.72vvO1RHWFDaGZmgXeUhOXkJ71kUpPW5fGoS', 'Amol', 'Vidhate', '09823880144', 'AP Janori, TAL Dindori, Dist nashik', 'staff', 'active', '2025-07-19 16:23:17', '2025-07-19 16:23:17'),
(6, 'sarang', 'sarang@gmail.com', '$2y$10$5d9JOSEOueYl1Kl7sXVFJ.s4SvW.jfALQLbuRnv4iA4FvWOxL7gBy', 'sarang', 'govardhane', '3445456787', 'nashik', 'customer', 'active', '2025-07-19 17:12:31', '2025-07-19 17:12:31'),
(7, '', 'VidhateAmol47@gmail.com', '$2y$10$90pGbyFTsXhEVy77eJcNgeqZP002/2eyrjhbsBCreGRRlCUD3FmZ2', 'Amol Vidhate', '', '09823880144', 'AP Janori, TAL Dindori, Dist nashik', 'customer', 'active', '2025-07-20 04:31:08', '2025-07-20 04:31:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_number` (`room_number`);

--
-- Indexes for table `staff_assignments`
--
ALTER TABLE `staff_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `staff_assignments`
--
ALTER TABLE `staff_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `staff_assignments`
--
ALTER TABLE `staff_assignments`
  ADD CONSTRAINT `staff_assignments_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
