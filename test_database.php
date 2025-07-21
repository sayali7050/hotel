<?php
// Test Database Connection and Booking System
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Database Connection Test</h2>";

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'hotel';

try {
    // Test connection
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
    // Test bookings table structure
    $stmt = $pdo->query("DESCRIBE bookings");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Bookings Table Structure:</h3>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . $column['Field'] . "</td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . $column['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Test users table
    $stmt = $pdo->query("DESCRIBE users");
    $userColumns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h3>Users Table Fields:</h3>";
    echo "<ul>";
    foreach ($userColumns as $column) {
        echo "<li>$column</li>";
    }
    echo "</ul>";
    
    // Test sample booking creation
    echo "<h3>Testing Sample Booking Creation:</h3>";
    
    // First, create a test user
    $testUserData = [
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
    
    $userSql = "INSERT INTO users (username, email, password, first_name, last_name, phone, address, role, status) 
                VALUES (:username, :email, :password, :first_name, :last_name, :phone, :address, :role, :status)";
    
    $userStmt = $pdo->prepare($userSql);
    $userStmt->execute($testUserData);
    $userId = $pdo->lastInsertId();
    
    echo "<p style='color: green;'>✓ Test user created with ID: $userId</p>";
    
    // Get a room
    $roomStmt = $pdo->query("SELECT id FROM rooms LIMIT 1");
    $room = $roomStmt->fetch(PDO::FETCH_ASSOC);
    
    if ($room) {
        // Create test booking
        $testBookingData = [
            'user_id' => $userId,
            'room_id' => $room['id'],
            'check_in_date' => date('Y-m-d', strtotime('+1 day')),
            'check_out_date' => date('Y-m-d', strtotime('+2 days')),
            'adults' => 2,
            'children' => 0,
            'rooms' => 1,
            'total_amount' => 200.00,
            'status' => 'pending',
            'special_requests' => 'Test booking',
            'guest_name' => 'Test Guest',
            'guest_email' => 'test@example.com',
            'guest_phone' => '1234567890',
            'guest_address' => 'Test Address',
            'payment_method' => 'credit_card'
        ];
        
        $bookingSql = "INSERT INTO bookings (user_id, room_id, check_in_date, check_out_date, adults, children, rooms, 
                       total_amount, status, special_requests, guest_name, guest_email, guest_phone, guest_address, payment_method) 
                       VALUES (:user_id, :room_id, :check_in_date, :check_out_date, :adults, :children, :rooms, 
                       :total_amount, :status, :special_requests, :guest_name, :guest_email, :guest_phone, :guest_address, :payment_method)";
        
        $bookingStmt = $pdo->prepare($bookingSql);
        $bookingStmt->execute($testBookingData);
        $bookingId = $pdo->lastInsertId();
        
        echo "<p style='color: green;'>✓ Test booking created with ID: $bookingId</p>";
        
        // Clean up test data
        $pdo->exec("DELETE FROM bookings WHERE id = $bookingId");
        $pdo->exec("DELETE FROM users WHERE id = $userId");
        
        echo "<p style='color: green;'>✓ Test data cleaned up</p>";
        echo "<h3 style='color: green;'>✅ All tests passed! Database is working correctly.</h3>";
        
    } else {
        echo "<p style='color: red;'>✗ No rooms found in database</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Database Error: " . $e->getMessage() . "</p>";
    echo "<p>Error Code: " . $e->getCode() . "</p>";
    echo "<p>File: " . $e->getFile() . "</p>";
    echo "<p>Line: " . $e->getLine() . "</p>";
}
?> 