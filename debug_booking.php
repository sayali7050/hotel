<?php
// Debug Booking Process
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Debug Booking Process</h2>";

// Simulate the booking data that would be submitted
$booking_data = [
    'room_id' => 1,
    'guest_name' => 'Test Guest',
    'guest_email' => 'test@example.com',
    'guest_phone' => '1234567890',
    'guest_address' => 'Test Address',
    'payment_method' => 'credit_card',
    'terms_accepted' => 'on'
];

echo "<h3>1. Testing Database Connection</h3>";

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'hotel';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color: green;'>✓ Database connection successful</p>";
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Database connection failed: " . $e->getMessage() . "</p>";
    exit;
}

echo "<h3>2. Testing Room Availability</h3>";

// Check if room exists
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->execute([$booking_data['room_id']]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if ($room) {
    echo "<p style='color: green;'>✓ Room found: " . $room['room_type'] . " (ID: " . $room['id'] . ")</p>";
} else {
    echo "<p style='color: red;'>✗ Room not found with ID: " . $booking_data['room_id'] . "</p>";
    exit;
}

echo "<h3>3. Testing Guest User Creation</h3>";

// Create test guest user
$guest_data = [
    'username' => 'guest_' . time() . '_' . rand(1000, 9999),
    'email' => $booking_data['guest_email'],
    'password' => password_hash(uniqid(), PASSWORD_DEFAULT),
    'first_name' => $booking_data['guest_name'],
    'last_name' => '',
    'phone' => $booking_data['guest_phone'],
    'address' => $booking_data['guest_address'],
    'role' => 'customer',
    'status' => 'active'
];

try {
    $userSql = "INSERT INTO users (username, email, password, first_name, last_name, phone, address, role, status) 
                VALUES (:username, :email, :password, :first_name, :last_name, :phone, :address, :role, :status)";
    
    $userStmt = $pdo->prepare($userSql);
    $userStmt->execute($guest_data);
    $userId = $pdo->lastInsertId();
    
    echo "<p style='color: green;'>✓ Guest user created with ID: $userId</p>";
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Guest user creation failed: " . $e->getMessage() . "</p>";
    exit;
}

echo "<h3>4. Testing Booking Creation</h3>";

// Create test booking
$search_data = [
    'check_in_date' => date('Y-m-d', strtotime('+1 day')),
    'check_out_date' => date('Y-m-d', strtotime('+2 days')),
    'adults' => 2,
    'children' => 0,
    'rooms' => 1,
    'nights' => 1,
    'special_requests' => 'Test booking'
];

$total_amount = $room['price_per_night'] * $search_data['nights'];

$booking_insert_data = [
    'user_id' => $userId,
    'room_id' => $room['id'],
    'check_in_date' => $search_data['check_in_date'],
    'check_out_date' => $search_data['check_out_date'],
    'adults' => $search_data['adults'],
    'children' => $search_data['children'],
    'rooms' => $search_data['rooms'],
    'total_amount' => $total_amount,
    'status' => 'pending',
    'special_requests' => $search_data['special_requests'],
    'guest_name' => $booking_data['guest_name'],
    'guest_email' => $booking_data['guest_email'],
    'guest_phone' => $booking_data['guest_phone'],
    'guest_address' => $booking_data['guest_address'],
    'payment_method' => $booking_data['payment_method']
];

try {
    $bookingSql = "INSERT INTO bookings (user_id, room_id, check_in_date, check_out_date, adults, children, rooms, 
                   total_amount, status, special_requests, guest_name, guest_email, guest_phone, guest_address, payment_method) 
                   VALUES (:user_id, :room_id, :check_in_date, :check_out_date, :adults, :children, :rooms, 
                   :total_amount, :status, :special_requests, :guest_name, :guest_email, :guest_phone, :guest_address, :payment_method)";
    
    $bookingStmt = $pdo->prepare($bookingSql);
    $bookingStmt->execute($booking_insert_data);
    $bookingId = $pdo->lastInsertId();
    
    echo "<p style='color: green;'>✓ Booking created with ID: $bookingId</p>";
    echo "<p>Total Amount: $" . number_format($total_amount, 2) . "</p>";
    
    // Show the created booking
    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ?");
    $stmt->execute([$bookingId]);
    $createdBooking = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<h4>Created Booking Details:</h4>";
    echo "<pre>" . print_r($createdBooking, true) . "</pre>";
    
    // Clean up test data
    $pdo->exec("DELETE FROM bookings WHERE id = $bookingId");
    $pdo->exec("DELETE FROM users WHERE id = $userId");
    
    echo "<p style='color: green;'>✓ Test data cleaned up</p>";
    echo "<h3 style='color: green;'>✅ All tests passed! Booking system is working correctly.</h3>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Booking creation failed: " . $e->getMessage() . "</p>";
    echo "<p>Error Code: " . $e->getCode() . "</p>";
    
    // Clean up user if booking failed
    $pdo->exec("DELETE FROM users WHERE id = $userId");
    
    // Show the SQL that failed
    echo "<h4>Failed SQL:</h4>";
    echo "<pre>$bookingSql</pre>";
    
    echo "<h4>Data that was being inserted:</h4>";
    echo "<pre>" . print_r($booking_insert_data, true) . "</pre>";
}
?> 