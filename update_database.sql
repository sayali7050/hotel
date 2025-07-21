-- Database Update Script for Professional Booking System
-- Run this script to add new fields to existing bookings table

USE hotel;

-- Add new fields to bookings table
ALTER TABLE bookings 
ADD COLUMN adults INT NOT NULL DEFAULT 1 AFTER check_out_date,
ADD COLUMN children INT NOT NULL DEFAULT 0 AFTER adults,
ADD COLUMN rooms INT NOT NULL DEFAULT 1 AFTER children,
ADD COLUMN guest_name VARCHAR(100) AFTER special_requests,
ADD COLUMN guest_email VARCHAR(100) AFTER guest_name,
ADD COLUMN guest_phone VARCHAR(20) AFTER guest_email,
ADD COLUMN guest_address TEXT AFTER guest_phone,
ADD COLUMN payment_method ENUM('credit_card', 'paypal', 'cash', 'debit_card', 'online') DEFAULT 'credit_card' AFTER guest_address;

-- Update existing bookings to have default values
UPDATE bookings SET 
adults = 1,
children = 0,
rooms = 1,
payment_method = 'credit_card'
WHERE adults IS NULL OR children IS NULL OR rooms IS NULL OR payment_method IS NULL;

-- Verify the update
DESCRIBE bookings; 