<?php
/**
 * Hotel Management System - Improvements Setup Script
 * 
 * This script helps implement all the improvements to your hotel management system.
 * Run this script after uploading all the new files to apply the database changes
 * and configure the system properly.
 * 
 * IMPORTANT: Make sure to backup your database before running this script!
 */

// Prevent direct browser access in production
if (php_sapi_name() !== 'cli' && !isset($_GET['setup_key']) || $_GET['setup_key'] !== 'hotel_setup_2024') {
    die('Access denied. Run from command line or provide setup key.');
}

echo "=== Hotel Management System Improvements Setup ===\n\n";

// Database configuration
$db_config = [
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'hotel',
    'dbdriver' => 'mysqli'
];

// Connect to database
$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error . "\n");
}

echo "✓ Database connection successful\n";

// Read and execute the database update script
$sql_file = 'database_complete_update.sql';
if (!file_exists($sql_file)) {
    die("Error: {$sql_file} not found. Please make sure all files are uploaded.\n");
}

$sql_content = file_get_contents($sql_file);
$statements = explode(';', $sql_content);

echo "\n=== Applying Database Updates ===\n";

$success_count = 0;
$error_count = 0;

foreach ($statements as $statement) {
    $statement = trim($statement);
    if (empty($statement) || substr($statement, 0, 2) === '--') {
        continue;
    }
    
    if ($mysqli->query($statement)) {
        $success_count++;
        echo "✓ Executed: " . substr($statement, 0, 50) . "...\n";
    } else {
        $error_count++;
        echo "✗ Error: " . $mysqli->error . "\n";
        echo "   Statement: " . substr($statement, 0, 100) . "...\n";
    }
}

echo "\n=== Database Update Summary ===\n";
echo "Successful statements: {$success_count}\n";
echo "Failed statements: {$error_count}\n";

if ($error_count > 0) {
    echo "\nWarning: Some database updates failed. Please review the errors above.\n";
}

// Initialize room inventory
echo "\n=== Initializing Room Inventory ===\n";

$room_types_query = "SELECT DISTINCT room_type FROM rooms WHERE status != 'maintenance'";
$room_types_result = $mysqli->query($room_types_query);

if ($room_types_result) {
    while ($row = $room_types_result->fetch_assoc()) {
        $room_type = $row['room_type'];
        
        // Get total rooms for this type
        $count_query = "SELECT COUNT(*) as total FROM rooms WHERE room_type = '{$room_type}' AND status != 'maintenance'";
        $count_result = $mysqli->query($count_query);
        $total_rooms = $count_result->fetch_assoc()['total'];
        
        // Initialize inventory for next 365 days
        for ($i = 0; $i <= 365; $i++) {
            $date = date('Y-m-d', strtotime("+{$i} days"));
            
            $check_query = "SELECT id FROM room_inventory WHERE room_type = '{$room_type}' AND date = '{$date}'";
            $exists = $mysqli->query($check_query)->num_rows > 0;
            
            if (!$exists) {
                $insert_query = "INSERT INTO room_inventory (room_type, date, total_rooms, booked_rooms, blocked_rooms) 
                                VALUES ('{$room_type}', '{$date}', {$total_rooms}, 0, 0)";
                $mysqli->query($insert_query);
            }
        }
        
        echo "✓ Initialized inventory for room type: {$room_type}\n";
    }
}

// Update existing bookings with references
echo "\n=== Updating Existing Bookings ===\n";

$update_bookings = "UPDATE bookings SET booking_reference = CONCAT('BK', LPAD(id, 6, '0')) WHERE booking_reference IS NULL OR booking_reference = ''";
if ($mysqli->query($update_bookings)) {
    echo "✓ Updated booking references\n";
} else {
    echo "✗ Error updating booking references: " . $mysqli->error . "\n";
}

// Create default admin user if not exists
echo "\n=== Checking Admin User ===\n";

$admin_check = "SELECT id FROM users WHERE username = 'admin' AND role = 'admin'";
$admin_result = $mysqli->query($admin_check);

if ($admin_result->num_rows === 0) {
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $create_admin = "INSERT INTO users (username, email, password, first_name, last_name, role, status) 
                     VALUES ('admin', 'admin@hotel.com', '{$admin_password}', 'System', 'Administrator', 'admin', 'active')";
    
    if ($mysqli->query($create_admin)) {
        echo "✓ Created default admin user (username: admin, password: admin123)\n";
        echo "  IMPORTANT: Change the admin password after first login!\n";
    } else {
        echo "✗ Error creating admin user: " . $mysqli->error . "\n";
    }
} else {
    echo "✓ Admin user already exists\n";
}

// Set up default settings
echo "\n=== Configuring Default Settings ===\n";

$default_settings = [
    ['hotel_name', 'Grand Hotel', 'Hotel name displayed throughout the system', 'string'],
    ['hotel_email', 'info@grandhotel.com', 'Primary hotel email address', 'string'],
    ['hotel_phone', '+1-234-567-8900', 'Primary hotel phone number', 'string'],
    ['max_login_attempts', '5', 'Maximum failed login attempts before account lockout', 'number'],
    ['account_lockout_duration', '30', 'Account lockout duration in minutes', 'number'],
    ['loyalty_points_per_dollar', '1', 'Loyalty points earned per dollar spent', 'number'],
    ['loyalty_redemption_rate', '100', 'Points needed to redeem $1', 'number'],
    ['booking_cancellation_hours', '24', 'Hours before check-in when free cancellation is allowed', 'number'],
    ['enable_2fa', 'true', 'Enable two-factor authentication for admin users', 'boolean'],
    ['password_min_length', '8', 'Minimum password length', 'number']
];

foreach ($default_settings as $setting) {
    list($key, $value, $description, $type) = $setting;
    
    $check_setting = "SELECT id FROM settings WHERE `key` = '{$key}'";
    $setting_exists = $mysqli->query($check_setting)->num_rows > 0;
    
    if (!$setting_exists) {
        $insert_setting = "INSERT INTO settings (`key`, `value`, description, type) 
                          VALUES ('{$key}', '{$value}', '{$description}', '{$type}')";
        
        if ($mysqli->query($insert_setting)) {
            echo "✓ Added setting: {$key}\n";
        } else {
            echo "✗ Error adding setting {$key}: " . $mysqli->error . "\n";
        }
    }
}

// File permissions check
echo "\n=== Checking File Permissions ===\n";

$directories_to_check = [
    'application/logs',
    'application/cache',
    'assets/uploads'
];

foreach ($directories_to_check as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "✓ Created directory: {$dir}\n";
        } else {
            echo "✗ Failed to create directory: {$dir}\n";
        }
    }
    
    if (is_writable($dir)) {
        echo "✓ Directory writable: {$dir}\n";
    } else {
        echo "✗ Directory not writable: {$dir}\n";
        echo "  Please run: chmod 755 {$dir}\n";
    }
}

// Configuration recommendations
echo "\n=== Configuration Recommendations ===\n";

echo "1. Email Configuration:\n";
echo "   - Update SMTP settings in Admin Panel > Settings\n";
echo "   - Test email functionality\n\n";

echo "2. Security Configuration:\n";
echo "   - Change default admin password\n";
echo "   - Configure 2FA settings\n";
echo "   - Set up SSL/HTTPS\n";
echo "   - Review IP whitelist/blacklist settings\n\n";

echo "3. Hotel Information:\n";
echo "   - Update hotel name, address, and contact information\n";
echo "   - Upload hotel logo\n";
echo "   - Configure room types and rates\n\n";

echo "4. System Maintenance:\n";
echo "   - Set up automated backups\n";
echo "   - Configure log rotation\n";
echo "   - Schedule loyalty points expiration (cron job)\n\n";

// Close database connection
$mysqli->close();

echo "=== Setup Complete ===\n\n";

echo "Your hotel management system has been successfully updated with the following improvements:\n\n";

echo "✓ Enhanced Authentication System\n";
echo "  - 2FA support\n";
echo "  - Password reset functionality\n";
echo "  - Account lockout protection\n";
echo "  - Security logging\n\n";

echo "✓ Improved Room Availability System\n";
echo "  - Inventory-based room management\n";
echo "  - Overbooking prevention\n";
echo "  - Better availability checking\n\n";

echo "✓ Complete Email System\n";
echo "  - SMTP configuration\n";
echo "  - Email templates\n";
echo "  - Bulk email support\n";
echo "  - Email logging\n\n";

echo "✓ Loyalty Points System\n";
echo "  - Points earning and redemption\n";
echo "  - Tier-based benefits\n";
echo "  - Transaction logging\n\n";

echo "✓ Enhanced Security Features\n";
echo "  - CSRF protection\n";
echo "  - Input sanitization\n";
echo "  - Rate limiting\n";
echo "  - Security event logging\n\n";

echo "✓ Additional Features\n";
echo "  - Coupon system\n";
echo "  - Waitlist management\n";
echo "  - Settings management\n";
echo "  - Audit logging\n\n";

echo "Next Steps:\n";
echo "1. Login to admin panel with username 'admin' and password 'admin123'\n";
echo "2. Change the admin password immediately\n";
echo "3. Configure email settings\n";
echo "4. Update hotel information\n";
echo "5. Test all functionality\n\n";

echo "For support or questions, please refer to the documentation.\n";

?>