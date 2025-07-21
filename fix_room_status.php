<?php
// Fix Room Status Script
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Fixing Room Status</h2>";

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'hotel';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
    // Check current status of Room ID 5
    echo "<h3>Current Status of Room ID 5</h3>";
    $stmt = $pdo->prepare("SELECT id, room_number, room_type, status FROM rooms WHERE id = ?");
    $stmt->execute([5]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($room) {
        echo "<p><strong>Room ID:</strong> " . $room['id'] . "</p>";
        echo "<p><strong>Room Number:</strong> " . $room['room_number'] . "</p>";
        echo "<p><strong>Room Type:</strong> " . $room['room_type'] . "</p>";
        echo "<p><strong>Current Status:</strong> <span style='color: red;'>" . $room['status'] . "</span></p>";
        
        if ($room['status'] !== 'available') {
            echo "<h3>Fixing Room Status</h3>";
            
            // Update room status to available
            $updateSql = "UPDATE rooms SET status = 'available' WHERE id = ?";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute([5]);
            
            echo "<p style='color: green;'>✓ Room status updated to 'available'</p>";
            
            // Verify the update
            $stmt = $pdo->prepare("SELECT status FROM rooms WHERE id = ?");
            $stmt->execute([5]);
            $updatedRoom = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo "<p><strong>New Status:</strong> <span style='color: green;'>" . $updatedRoom['status'] . "</span></p>";
            
            echo "<h3 style='color: green;'>✅ Room ID 5 is now available for booking!</h3>";
            
        } else {
            echo "<p style='color: green;'>✓ Room is already available</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ Room ID 5 not found</p>";
    }
    
    // Show all available rooms
    echo "<h3>All Available Rooms</h3>";
    $stmt = $pdo->query("SELECT id, room_number, room_type, status, price_per_night FROM rooms WHERE status = 'available' ORDER BY id");
    $available_rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($available_rooms)) {
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>ID</th><th>Room Number</th><th>Type</th><th>Price</th><th>Status</th></tr>";
        foreach ($available_rooms as $room) {
            echo "<tr>";
            echo "<td>" . $room['id'] . "</td>";
            echo "<td>" . $room['room_number'] . "</td>";
            echo "<td>" . $room['room_type'] . "</td>";
            echo "<td>$" . $room['price_per_night'] . "</td>";
            echo "<td style='color: green;'>" . $room['status'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<h3 style='color: green;'>✅ You can now book any of these rooms!</h3>";
        echo "<p><strong>Next Steps:</strong></p>";
        echo "<ol>";
        echo "<li>Go to <a href='http://localhost/hotel/booking' target='_blank'>Booking Page</a></li>";
        echo "<li>Search for availability</li>";
        echo "<li>Select an available room</li>";
        echo "<li>Complete the booking form</li>";
        echo "</ol>";
        
    } else {
        echo "<p style='color: red;'>✗ No rooms are available for booking</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Database error: " . $e->getMessage() . "</p>";
}
?> 