<?php
// Diagnose Booking Error with Current Database
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Diagnosing Booking Error</h2>";

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'hotel';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
    // Step 1: Check Room ID 5
    echo "<h3>Step 1: Checking Room ID 5</h3>";
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
    $stmt->execute([5]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($room) {
        echo "<p style='color: green;'>✓ Room found: " . $room['room_type'] . " (Room " . $room['room_number'] . ")</p>";
        echo "<p><strong>Status:</strong> " . $room['status'] . "</p>";
        echo "<p><strong>Price:</strong> $" . $room['price_per_night'] . "</p>";
        echo "<p><strong>Capacity:</strong> " . $room['capacity'] . " guests</p>";
        
        if ($room['status'] !== 'available') {
            echo "<p style='color: red;'>⚠ <strong>ISSUE FOUND:</strong> Room is not available for booking (Status: " . $room['status'] . ")</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ Room not found with ID: 5</p>";
        exit;
    }
    
    // Step 2: Check available rooms
    echo "<h3>Step 2: Checking Available Rooms</h3>";
    $stmt = $pdo->query("SELECT id, room_number, room_type, status, price_per_night FROM rooms WHERE status = 'available'");
    $available_rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($available_rooms)) {
        echo "<p style='color: red;'>✗ No rooms are available for booking</p>";
    } else {
        echo "<p style='color: green;'>✓ Available rooms:</p>";
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>ID</th><th>Room Number</th><th>Type</th><th>Price</th><th>Status</th></tr>";
        foreach ($available_rooms as $room) {
            echo "<tr>";
            echo "<td>" . $room['id'] . "</td>";
            echo "<td>" . $room['room_number'] . "</td>";
            echo "<td>" . $room['room_type'] . "</td>";
            echo "<td>$" . $room['price_per_night'] . "</td>";
            echo "<td>" . $room['status'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Step 3: Check bookings table structure
    echo "<h3>Step 3: Checking Bookings Table Structure</h3>";
    $stmt = $pdo->query("DESCRIBE bookings");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $required_columns = ['id', 'user_id', 'room_id', 'check_in_date', 'check_out_date', 'adults', 'children', 'rooms', 'total_amount', 'status', 'special_requests', 'guest_name', 'guest_email', 'guest_phone', 'guest_address', 'payment_method'];
    
    $missing_columns = [];
    foreach ($required_columns as $required) {
        $found = false;
        foreach ($columns as $column) {
            if ($column['Field'] === $required) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            $missing_columns[] = $required;
        }
    }
    
    if (empty($missing_columns)) {
        echo "<p style='color: green;'>✓ All required columns exist in bookings table</p>";
    } else {
        echo "<p style='color: red;'>✗ Missing columns: " . implode(', ', $missing_columns) . "</p>";
    }
    
    // Step 4: Test booking creation with available room
    echo "<h3>Step 4: Testing Booking Creation</h3>";
    
    if (!empty($available_rooms)) {
        $test_room = $available_rooms[0]; // Use first available room
        
        echo "<p>Testing with room ID: " . $test_room['id'] . " (" . $test_room['room_type'] . ")</p>";
        
        // Create test user
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
        
        try {
            $userSql = "INSERT INTO users (username, email, password, first_name, last_name, phone, address, role, status) 
                        VALUES (:username, :email, :password, :first_name, :last_name, :phone, :address, :role, :status)";
            
            $userStmt = $pdo->prepare($userSql);
            $userStmt->execute($guest_data);
            $userId = $pdo->lastInsertId();
            
            echo "<p style='color: green;'>✓ Test user created with ID: $userId</p>";
            
            // Create test booking
            $booking_data = [
                'user_id' => $userId,
                'room_id' => $test_room['id'],
                'check_in_date' => date('Y-m-d', strtotime('+1 day')),
                'check_out_date' => date('Y-m-d', strtotime('+2 days')),
                'adults' => 2,
                'children' => 0,
                'rooms' => 1,
                'total_amount' => $test_room['price_per_night'],
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
            $bookingStmt->execute($booking_data);
            $bookingId = $pdo->lastInsertId();
            
            echo "<p style='color: green;'>✓ Test booking created successfully with ID: $bookingId</p>";
            
            // Clean up
            $pdo->exec("DELETE FROM bookings WHERE id = $bookingId");
            $pdo->exec("DELETE FROM users WHERE id = $userId");
            
            echo "<p style='color: green;'>✓ Test data cleaned up</p>";
            echo "<h3 style='color: green;'>✅ Booking system is working correctly!</h3>";
            
        } catch (PDOException $e) {
            echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
            echo "<p>Error Code: " . $e->getCode() . "</p>";
            
            // Clean up user if booking failed
            if (isset($userId)) {
                $pdo->exec("DELETE FROM users WHERE id = $userId");
            }
        }
    } else {
        echo "<p style='color: red;'>✗ Cannot test booking - no available rooms</p>";
    }
    
    // Step 5: Recommendations
    echo "<h3>Step 5: Recommendations</h3>";
    
    if ($room['status'] !== 'available') {
        echo "<p style='color: orange;'>⚠ <strong>MAIN ISSUE:</strong> Room ID 5 is in '" . $room['status'] . "' status</p>";
        echo "<p><strong>Solution:</strong> Either:</p>";
        echo "<ul>";
        echo "<li>Change room status to 'available' in the database</li>";
        echo "<li>Or use a different room ID that is available</li>";
        echo "</ul>";
        
        echo "<p><strong>To fix room status, run this SQL:</strong></p>";
        echo "<pre>UPDATE rooms SET status = 'available' WHERE id = 5;</pre>";
    }
    
    if (!empty($available_rooms)) {
        echo "<p style='color: green;'>✓ <strong>WORKING SOLUTION:</strong> Use one of these available room IDs:</p>";
        echo "<ul>";
        foreach ($available_rooms as $room) {
            echo "<li>Room ID " . $room['id'] . " (" . $room['room_type'] . " - $" . $room['price_per_night'] . ")</li>";
        }
        echo "</ul>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Database connection failed: " . $e->getMessage() . "</p>";
}
?> 