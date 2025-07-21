<?php
// Test CodeIgniter Booking Controller
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Testing CodeIgniter Booking Controller</h2>";

// Simulate CodeIgniter environment
define('BASEPATH', 'system/');
define('APPPATH', 'application/');

// Include CodeIgniter core files
require_once 'system/core/Common.php';
require_once 'system/database/DB.php';
require_once 'system/database/DB_driver.php';
require_once 'system/database/DB_query_builder.php';

// Load database configuration
$db_config = [
    'dsn' => '',
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'hotel',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => TRUE,
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
];

try {
    // Initialize database
    $db = new CI_DB($db_config);
    $db->initialize();
    
    echo "<p style='color: green;'>✓ CodeIgniter database initialized</p>";
    
    // Test booking model
    require_once 'application/models/Booking_model.php';
    require_once 'application/models/User_model.php';
    require_once 'application/models/Room_model.php';
    
    $booking_model = new Booking_model();
    $user_model = new User_model();
    $room_model = new Room_model();
    
    echo "<p style='color: green;'>✓ Models loaded successfully</p>";
    
    // Test room retrieval
    $room = $room_model->get_room_by_id(1);
    if ($room) {
        echo "<p style='color: green;'>✓ Room found: " . $room->room_type . "</p>";
    } else {
        echo "<p style='color: red;'>✗ Room not found</p>";
        exit;
    }
    
    // Test user creation
    $guest_data = [
        'username' => 'test_guest_' . time(),
        'email' => 'test' . time() . '@example.com',
        'password' => password_hash('test123', PASSWORD_DEFAULT),
        'first_name' => 'Test',
        'last_name' => 'Guest',
        'phone' => '1234567890',
        'address' => 'Test Address',
        'role' => 'customer',
        'status' => 'active'
    ];
    
    $user_id = $user_model->create_user($guest_data);
    if ($user_id) {
        echo "<p style='color: green;'>✓ Test user created with ID: $user_id</p>";
    } else {
        echo "<p style='color: red;'>✗ User creation failed</p>";
        exit;
    }
    
    // Test booking creation
    $booking_data = [
        'user_id' => $user_id,
        'room_id' => $room->id,
        'check_in_date' => date('Y-m-d', strtotime('+1 day')),
        'check_out_date' => date('Y-m-d', strtotime('+2 days')),
        'adults' => 2,
        'children' => 0,
        'rooms' => 1,
        'total_amount' => $room->price_per_night,
        'status' => 'pending',
        'special_requests' => 'Test booking',
        'guest_name' => 'Test Guest',
        'guest_email' => 'test@example.com',
        'guest_phone' => '1234567890',
        'guest_address' => 'Test Address',
        'payment_method' => 'credit_card'
    ];
    
    $booking_id = $booking_model->create_booking($booking_data);
    if ($booking_id) {
        echo "<p style='color: green;'>✓ Test booking created with ID: $booking_id</p>";
        
        // Clean up
        $booking_model->delete_booking($booking_id);
        $user_model->delete_user($user_id);
        
        echo "<p style='color: green;'>✓ Test data cleaned up</p>";
        echo "<h3 style='color: green;'>✅ CodeIgniter booking system is working correctly!</h3>";
    } else {
        echo "<p style='color: red;'>✗ Booking creation failed</p>";
        
        // Clean up user
        $user_model->delete_user($user_id);
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . "</p>";
    echo "<p>Line: " . $e->getLine() . "</p>";
}
?> 