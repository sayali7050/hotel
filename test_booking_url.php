<?php
// Test Booking URL Access
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Testing Booking URL Access</h2>";

// Test if CodeIgniter is working
echo "<h3>1. Testing CodeIgniter Base URL</h3>";
echo "<p><strong>Base URL:</strong> " . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost') . "/hotel/</p>";

// Test different booking URLs
echo "<h3>2. Booking URLs to Test</h3>";
echo "<ul>";
echo "<li><a href='http://localhost/hotel/booking' target='_blank'>http://localhost/hotel/booking</a> (Correct)</li>";
echo "<li><a href='http://localhost/hotel/booking.php' target='_blank'>http://localhost/hotel/booking.php</a> (Wrong - will give 404)</li>";
echo "<li><a href='http://localhost/hotel/index.php/booking' target='_blank'>http://localhost/hotel/index.php/booking</a> (Alternative)</li>";
echo "</ul>";

// Test if the booking controller exists
echo "<h3>3. Checking Booking Controller</h3>";
$controller_path = 'application/controllers/Booking.php';
if (file_exists($controller_path)) {
    echo "<p style='color: green;'>✓ Booking controller exists at: $controller_path</p>";
} else {
    echo "<p style='color: red;'>✗ Booking controller not found at: $controller_path</p>";
}

// Test if the booking view exists
echo "<h3>4. Checking Booking Views</h3>";
$view_paths = [
    'application/views/booking/index.php',
    'application/views/booking/select_room.php',
    'application/views/booking/book_room.php',
    'application/views/booking/confirmation.php'
];

foreach ($view_paths as $view_path) {
    if (file_exists($view_path)) {
        echo "<p style='color: green;'>✓ View exists: $view_path</p>";
    } else {
        echo "<p style='color: red;'>✗ View missing: $view_path</p>";
    }
}

// Test routes configuration
echo "<h3>5. Routes Configuration</h3>";
$routes_path = 'application/config/routes.php';
if (file_exists($routes_path)) {
    echo "<p style='color: green;'>✓ Routes file exists</p>";
    
    // Check if booking route is configured
    $routes_content = file_get_contents($routes_path);
    if (strpos($routes_content, "'booking' => 'booking/index'") !== false) {
        echo "<p style='color: green;'>✓ Booking route is configured</p>";
    } else {
        echo "<p style='color: red;'>✗ Booking route not found in routes.php</p>";
    }
} else {
    echo "<p style='color: red;'>✗ Routes file not found</p>";
}

// Test .htaccess
echo "<h3>6. .htaccess Configuration</h3>";
$htaccess_path = '.htaccess';
if (file_exists($htaccess_path)) {
    echo "<p style='color: green;'>✓ .htaccess file exists</p>";
    
    $htaccess_content = file_get_contents($htaccess_path);
    if (strpos($htaccess_content, 'RewriteEngine On') !== false) {
        echo "<p style='color: green;'>✓ URL rewriting is enabled</p>";
    } else {
        echo "<p style='color: red;'>✗ URL rewriting not configured</p>";
    }
} else {
    echo "<p style='color: red;'>✗ .htaccess file not found</p>";
}

echo "<h3>7. Correct URLs to Use</h3>";
echo "<p><strong>Main Booking Page:</strong> <a href='http://localhost/hotel/booking' target='_blank'>http://localhost/hotel/booking</a></p>";
echo "<p><strong>Room Selection:</strong> <a href='http://localhost/hotel/booking/select-room' target='_blank'>http://localhost/hotel/booking/select-room</a></p>";
echo "<p><strong>Book Specific Room:</strong> <a href='http://localhost/hotel/booking/book-room/1' target='_blank'>http://localhost/hotel/booking/book-room/1</a></p>";

echo "<h3>8. Troubleshooting</h3>";
echo "<p>If you're still getting 404 errors:</p>";
echo "<ol>";
echo "<li>Make sure XAMPP Apache is running</li>";
echo "<li>Check that mod_rewrite is enabled in Apache</li>";
echo "<li>Try accessing: <a href='http://localhost/hotel/index.php/booking' target='_blank'>http://localhost/hotel/index.php/booking</a></li>";
echo "<li>Check Apache error logs for more details</li>";
echo "</ol>";
?> 