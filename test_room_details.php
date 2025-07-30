<?php
// Test Room Details Functionality
echo "<h2>Room Details Test</h2>";

// Database configuration (same as check_db.php)
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'hotel';

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
    // Check if rooms table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'rooms'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✓ Rooms table exists</p>";
    } else {
        echo "<p style='color: red;'>✗ Rooms table does not exist</p>";
        exit;
    }
    
    // Check room count
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM rooms");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>Total rooms: " . $result['count'] . "</p>";
    
    // If no rooms, add sample data
    if ($result['count'] == 0) {
        echo "<p>Adding sample room data...</p>";
        $rooms = [
            ['101', 'Standard Room', 2, 120.00, 'Comfortable standard room with modern amenities', 'Wi-Fi, Air Conditioning, TV, Mini Fridge'],
            ['201', 'Deluxe Room', 3, 180.00, 'Spacious deluxe room with city view', 'Wi-Fi, Air Conditioning, TV, Mini Bar, Balcony'],
            ['301', 'Presidential Suite', 4, 350.00, 'Luxury presidential suite with premium amenities', 'Wi-Fi, Air Conditioning, TV, Mini Bar, Jacuzzi, Butler Service'],
            ['401', 'Executive Suite', 4, 280.00, 'Executive suite perfect for business travelers', 'Wi-Fi, Air Conditioning, TV, Mini Bar, Work Desk, Meeting Area']
        ];
        
        $sql = 'INSERT INTO rooms (room_number, room_type, capacity, price_per_night, description, amenities) VALUES (?, ?, ?, ?, ?, ?)';
        $stmt = $pdo->prepare($sql);
        
        foreach ($rooms as $room) {
            $stmt->execute($room);
        }
        echo "<p style='color: green;'>✓ Sample rooms added successfully!</p>";
    }
    
    // Check reviews table
    $stmt = $pdo->query("SHOW TABLES LIKE 'reviews'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✓ Reviews table exists</p>";
        
        // Check if status and admin_reply columns exist (from moderation update)
        $stmt = $pdo->query("SHOW COLUMNS FROM reviews LIKE 'status'");
        if ($stmt->rowCount() > 0) {
            echo "<p style='color: green;'>✓ Reviews moderation columns exist</p>";
        } else {
            echo "<p style='color: orange;'>⚠ Reviews moderation columns missing - run update_reviews_moderation.sql</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ Reviews table does not exist</p>";
    }
    
    // Display sample rooms
    $stmt = $pdo->query("SELECT * FROM rooms LIMIT 5");
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Sample Rooms:</h3>";
    foreach ($rooms as $room) {
        echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
        echo "<h4>Room {$room['room_number']} - {$room['room_type']}</h4>";
        echo "<p><strong>Capacity:</strong> {$room['capacity']} guests</p>";
        echo "<p><strong>Price:</strong> $" . number_format($room['price_per_night'], 2) . "/night</p>";
        echo "<p><strong>Description:</strong> {$room['description']}</p>";
        echo "<p><strong>Room Details URL:</strong> <a href='index.php/room-details/{$room['id']}' target='_blank'>View Details</a></p>";
        echo "</div>";
    }
    
    echo "<h3>Testing URLs:</h3>";
    echo "<ul>";
    echo "<li><a href='index.php/room-details' target='_blank'>Room Details (Default - Room ID 1)</a></li>";
    echo "<li><a href='index.php/room-details/1' target='_blank'>Room Details - Room 1</a></li>";
    echo "<li><a href='index.php/room-details/2' target='_blank'>Room Details - Room 2</a></li>";
    echo "<li><a href='index.php/room-details/3' target='_blank'>Room Details - Room 3</a></li>";
    echo "<li><a href='index.php/room-details/999' target='_blank'>Room Details - Non-existent Room (should show 404)</a></li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
}
?>