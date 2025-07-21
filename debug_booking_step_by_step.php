<?php
// Step-by-Step Booking Debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Step-by-Step Booking Debug</h2>";

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'hotel';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
    // Step 1: Check if room exists
    echo "<h3>Step 1: Checking Room (ID: 5)</h3>";
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
    $stmt->execute([5]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($room) {
        echo "<p style='color: green;'>✓ Room found: " . $room['room_type'] . " (Price: $" . $room['price_per_night'] . ")</p>";
    } else {
        echo "<p style='color: red;'>✗ Room not found with ID: 5</p>";
        exit;
    }
    
    // Step 2: Check bookings table structure
    echo "<h3>Step 2: Checking Bookings Table Structure</h3>";
    $stmt = $pdo->query("DESCRIBE bookings");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
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
    
    // Step 3: Check users table structure
    echo "<h3>Step 3: Checking Users Table Structure</h3>";
    $stmt = $pdo->query("DESCRIBE users");
    $userColumns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($userColumns as $column) {
        echo "<tr>";
        echo "<td>" . $column['Field'] . "</td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . $column['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Step 4: Test user creation
    echo "<h3>Step 4: Testing User Creation</h3>";
    
    $guest_data = [
        'username' => 'guest_' . time() . '_' . rand(1000, 9999),
        'email' => 'test' . time() . '@example.com',
        'password' => password_hash(uniqid(), PASSWORD_DEFAULT),
        'first_name' => 'Test',
        'last_name' => 'Guest',
        'phone' => '1234567890',
        'address' => 'Test Address',
        'role' => 'customer',
        'status' => 'active'
    ];
    
    echo "<p>Attempting to create user with data:</p>";
    echo "<pre>" . print_r($guest_data, true) . "</pre>";
    
    try {
        $userSql = "INSERT INTO users (username, email, password, first_name, last_name, phone, address, role, status) 
                    VALUES (:username, :email, :password, :first_name, :last_name, :phone, :address, :role, :status)";
        
        $userStmt = $pdo->prepare($userSql);
        $userStmt->execute($guest_data);
        $userId = $pdo->lastInsertId();
        
        echo "<p style='color: green;'>✓ User created successfully with ID: $userId</p>";
        
        // Step 5: Test booking creation
        echo "<h3>Step 5: Testing Booking Creation</h3>";
        
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
        
        $booking_data = [
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
            'guest_name' => 'Test Guest',
            'guest_email' => 'test@example.com',
            'guest_phone' => '1234567890',
            'guest_address' => 'Test Address',
            'payment_method' => 'credit_card'
        ];
        
        echo "<p>Attempting to create booking with data:</p>";
        echo "<pre>" . print_r($booking_data, true) . "</pre>";
        
        $bookingSql = "INSERT INTO bookings (user_id, room_id, check_in_date, check_out_date, adults, children, rooms, 
                       total_amount, status, special_requests, guest_name, guest_email, guest_phone, guest_address, payment_method) 
                       VALUES (:user_id, :room_id, :check_in_date, :check_out_date, :adults, :children, :rooms, 
                       :total_amount, :status, :special_requests, :guest_name, :guest_email, :guest_phone, :guest_address, :payment_method)";
        
        $bookingStmt = $pdo->prepare($bookingSql);
        $bookingStmt->execute($booking_data);
        $bookingId = $pdo->lastInsertId();
        
        echo "<p style='color: green;'>✓ Booking created successfully with ID: $bookingId</p>";
        
        // Step 6: Verify the booking was created
        echo "<h3>Step 6: Verifying Created Booking</h3>";
        $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ?");
        $stmt->execute([$bookingId]);
        $createdBooking = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<p>Created booking details:</p>";
        echo "<pre>" . print_r($createdBooking, true) . "</pre>";
        
        // Step 7: Clean up
        echo "<h3>Step 7: Cleaning Up Test Data</h3>";
        $pdo->exec("DELETE FROM bookings WHERE id = $bookingId");
        $pdo->exec("DELETE FROM users WHERE id = $userId");
        
        echo "<p style='color: green;'>✓ Test data cleaned up</p>";
        echo "<h3 style='color: green;'>✅ All steps completed successfully!</h3>";
        
    } catch (PDOException $e) {
        echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
        echo "<p>Error Code: " . $e->getCode() . "</p>";
        echo "<p>File: " . $e->getFile() . "</p>";
        echo "<p>Line: " . $e->getLine() . "</p>";
        
        // Show the SQL that failed
        if (isset($bookingSql)) {
            echo "<h4>Failed SQL:</h4>";
            echo "<pre>$bookingSql</pre>";
        }
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Database connection failed: " . $e->getMessage() . "</p>";
}
?> 