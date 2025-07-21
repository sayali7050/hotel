<?php
// Database Update Script for Professional Booking System
// This script adds new fields to the bookings table

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'hotel';

try {
    // Create connection
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Database Update for Professional Booking System</h2>";
    echo "<p>Updating bookings table with new fields...</p>";
    
    // Check if fields already exist
    $stmt = $pdo->query("DESCRIBE bookings");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $newFields = [
        'adults' => "ALTER TABLE bookings ADD COLUMN adults INT NOT NULL DEFAULT 1 AFTER check_out_date",
        'children' => "ALTER TABLE bookings ADD COLUMN children INT NOT NULL DEFAULT 0 AFTER adults",
        'rooms' => "ALTER TABLE bookings ADD COLUMN rooms INT NOT NULL DEFAULT 1 AFTER children",
        'guest_name' => "ALTER TABLE bookings ADD COLUMN guest_name VARCHAR(100) AFTER special_requests",
        'guest_email' => "ALTER TABLE bookings ADD COLUMN guest_email VARCHAR(100) AFTER guest_name",
        'guest_phone' => "ALTER TABLE bookings ADD COLUMN guest_phone VARCHAR(20) AFTER guest_email",
        'guest_address' => "ALTER TABLE bookings ADD COLUMN guest_address TEXT AFTER guest_phone",
        'payment_method' => "ALTER TABLE bookings ADD COLUMN payment_method ENUM('credit_card', 'paypal', 'cash', 'debit_card', 'online') DEFAULT 'credit_card' AFTER guest_address"
    ];
    
    $updated = false;
    
    foreach ($newFields as $field => $sql) {
        if (!in_array($field, $columns)) {
            try {
                $pdo->exec($sql);
                echo "<p style='color: green;'>✓ Added field: $field</p>";
                $updated = true;
            } catch (PDOException $e) {
                echo "<p style='color: red;'>✗ Error adding field $field: " . $e->getMessage() . "</p>";
            }
        } else {
            echo "<p style='color: blue;'>- Field already exists: $field</p>";
        }
    }
    
    if ($updated) {
        // Update existing bookings with default values
        try {
            $pdo->exec("UPDATE bookings SET 
                adults = 1,
                children = 0,
                rooms = 1,
                payment_method = 'credit_card'
                WHERE adults IS NULL OR children IS NULL OR rooms IS NULL OR payment_method IS NULL");
            echo "<p style='color: green;'>✓ Updated existing bookings with default values</p>";
        } catch (PDOException $e) {
            echo "<p style='color: red;'>✗ Error updating existing bookings: " . $e->getMessage() . "</p>";
        }
    }
    
    // Show final table structure
    echo "<h3>Final Bookings Table Structure:</h3>";
    $stmt = $pdo->query("DESCRIBE bookings");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . $column['Field'] . "</td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . $column['Default'] . "</td>";
        echo "<td>" . $column['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h3 style='color: green;'>✅ Database update completed successfully!</h3>";
    echo "<p><a href='booking'>Go to Booking System</a></p>";
    
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>Database Connection Error</h2>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "<p>Please check your database configuration.</p>";
}
?> 