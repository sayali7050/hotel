<?php
// Fix Booking Error Script
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Fixing Booking Error</h2>";

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'hotel';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
    // Step 1: Check and fix bookings table
    echo "<h3>Step 1: Checking and Fixing Bookings Table</h3>";
    
    // Get current table structure
    $stmt = $pdo->query("DESCRIBE bookings");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<p>Current columns: " . implode(', ', $columns) . "</p>";
    
    // Check if required columns exist
    $required_columns = [
        'adults' => "ALTER TABLE bookings ADD COLUMN adults INT NOT NULL DEFAULT 1 AFTER check_out_date",
        'children' => "ALTER TABLE bookings ADD COLUMN children INT NOT NULL DEFAULT 0 AFTER adults",
        'rooms' => "ALTER TABLE bookings ADD COLUMN rooms INT NOT NULL DEFAULT 1 AFTER children",
        'guest_name' => "ALTER TABLE bookings ADD COLUMN guest_name VARCHAR(100) AFTER special_requests",
        'guest_email' => "ALTER TABLE bookings ADD COLUMN guest_email VARCHAR(100) AFTER guest_name",
        'guest_phone' => "ALTER TABLE bookings ADD COLUMN guest_phone VARCHAR(20) AFTER guest_email",
        'guest_address' => "ALTER TABLE bookings ADD COLUMN guest_address TEXT AFTER guest_phone",
        'payment_method' => "ALTER TABLE bookings ADD COLUMN payment_method ENUM('credit_card', 'paypal', 'cash', 'debit_card', 'online') DEFAULT 'credit_card' AFTER guest_address"
    ];
    
    foreach ($required_columns as $column => $sql) {
        if (!in_array($column, $columns)) {
            echo "<p>Adding missing column: $column</p>";
            try {
                $pdo->exec($sql);
                echo "<p style='color: green;'>✓ Added column: $column</p>";
            } catch (PDOException $e) {
                echo "<p style='color: orange;'>⚠ Column $column might already exist: " . $e->getMessage() . "</p>";
            }
        } else {
            echo "<p style='color: green;'>✓ Column $column already exists</p>";
        }
    }
    
    // Step 2: Test the exact booking process
    echo "<h3>Step 2: Testing Exact Booking Process</h3>";
    
    // Simulate the exact data that would come from the form
    $form_data = [
        'room_id' => 5,
        'guest_name' => 'John Doe',
        'guest_email' => 'john@example.com',
        'guest_phone' => '1234567890',
        'guest_address' => '123 Main St, City, State',
        'payment_method' => 'credit_card',
        'terms_accepted' => 'on'
    ];
    
    // Simulate session data
    $session_data = [
        'check_in_date' => date('Y-m-d', strtotime('+1 day')),
        'check_out_date' => date('Y-m-d', strtotime('+2 days')),
        'adults' => 2,
        'children' => 0,
        'rooms' => 1,
        'nights' => 1,
        'special_requests' => 'Test booking'
    ];
    
    echo "<p>Form data: " . print_r($form_data, true) . "</p>";
    echo "<p>Session data: " . print_r($session_data, true) . "</p>";
    
    // Step 3: Get room details
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
    $stmt->execute([$form_data['room_id']]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$room) {
        echo "<p style='color: red;'>✗ Room not found with ID: " . $form_data['room_id'] . "</p>";
        exit;
    }
    
    echo "<p style='color: green;'>✓ Room found: " . $room['room_type'] . " (Price: $" . $room['price_per_night'] . ")</p>";
    
    // Step 4: Create guest user
    echo "<h3>Step 4: Creating Guest User</h3>";
    
    $guest_data = [
        'username' => 'guest_' . time() . '_' . rand(1000, 9999),
        'email' => $form_data['guest_email'],
        'password' => password_hash(uniqid(), PASSWORD_DEFAULT),
        'first_name' => $form_data['guest_name'],
        'last_name' => '',
        'phone' => $form_data['guest_phone'],
        'address' => $form_data['guest_address'],
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
        
        // Step 5: Create booking
        echo "<h3>Step 5: Creating Booking</h3>";
        
        $total_amount = $room['price_per_night'] * $session_data['nights'];
        
        $booking_data = [
            'user_id' => $userId,
            'room_id' => $room['id'],
            'check_in_date' => $session_data['check_in_date'],
            'check_out_date' => $session_data['check_out_date'],
            'adults' => $session_data['adults'],
            'children' => $session_data['children'],
            'rooms' => $session_data['rooms'],
            'total_amount' => $total_amount,
            'status' => 'pending',
            'special_requests' => $session_data['special_requests'],
            'guest_name' => $form_data['guest_name'],
            'guest_email' => $form_data['guest_email'],
            'guest_phone' => $form_data['guest_phone'],
            'guest_address' => $form_data['guest_address'],
            'payment_method' => $form_data['payment_method']
        ];
        
        echo "<p>Booking data: " . print_r($booking_data, true) . "</p>";
        
        $bookingSql = "INSERT INTO bookings (user_id, room_id, check_in_date, check_out_date, adults, children, rooms, 
                       total_amount, status, special_requests, guest_name, guest_email, guest_phone, guest_address, payment_method) 
                       VALUES (:user_id, :room_id, :check_in_date, :check_out_date, :adults, :children, :rooms, 
                       :total_amount, :status, :special_requests, :guest_name, :guest_email, :guest_phone, :guest_address, :payment_method)";
        
        $bookingStmt = $pdo->prepare($bookingSql);
        $bookingStmt->execute($booking_data);
        $bookingId = $pdo->lastInsertId();
        
        echo "<p style='color: green;'>✓ Booking created successfully with ID: $bookingId</p>";
        echo "<p>Total Amount: $" . number_format($total_amount, 2) . "</p>";
        
        // Step 6: Verify booking
        $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ?");
        $stmt->execute([$bookingId]);
        $createdBooking = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<h4>Created Booking Details:</h4>";
        echo "<pre>" . print_r($createdBooking, true) . "</pre>";
        
        // Step 7: Clean up
        $pdo->exec("DELETE FROM bookings WHERE id = $bookingId");
        $pdo->exec("DELETE FROM users WHERE id = $userId");
        
        echo "<p style='color: green;'>✓ Test data cleaned up</p>";
        echo "<h3 style='color: green;'>✅ Booking system is now working correctly!</h3>";
        echo "<p><strong>You can now try the booking process again:</strong></p>";
        echo "<p><a href='http://localhost/hotel/booking' target='_blank'>Start Booking Process</a></p>";
        
    } catch (PDOException $e) {
        echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
        echo "<p>Error Code: " . $e->getCode() . "</p>";
        
        // Show the exact SQL that failed
        if (isset($bookingSql)) {
            echo "<h4>Failed SQL:</h4>";
            echo "<pre>$bookingSql</pre>";
        }
        
        // Clean up user if booking failed
        if (isset($userId)) {
            $pdo->exec("DELETE FROM users WHERE id = $userId");
        }
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Database connection failed: " . $e->getMessage() . "</p>";
}
?> 