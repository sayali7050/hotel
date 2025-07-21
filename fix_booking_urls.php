<?php
// Fix Booking URLs
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Booking URL Fix</h2>";

echo "<h3>🚨 The Problem</h3>";
echo "<p><strong>You're using:</strong> <code>http://localhost/hotel/booking.php</code></p>";
echo "<p style='color: red;'>❌ This is WRONG and will give you a 404 error!</p>";

echo "<h3>✅ The Solution</h3>";
echo "<p><strong>Use this instead:</strong> <code>http://localhost/hotel/booking</code></p>";
echo "<p style='color: green;'>✅ This is CORRECT and will work!</p>";

echo "<h3>🔗 Correct Booking URLs</h3>";
echo "<div style='background: #f0f8ff; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
echo "<p><strong>Main Booking Page:</strong></p>";
echo "<p><a href='http://localhost/hotel/booking' target='_blank' style='color: blue; text-decoration: underline;'>http://localhost/hotel/booking</a></p>";

echo "<p><strong>Room Selection:</strong></p>";
echo "<p><a href='http://localhost/hotel/booking/select-room' target='_blank' style='color: blue; text-decoration: underline;'>http://localhost/hotel/booking/select-room</a></p>";

echo "<p><strong>Book Specific Room (ID 1):</strong></p>";
echo "<p><a href='http://localhost/hotel/booking/book-room/1' target='_blank' style='color: blue; text-decoration: underline;'>http://localhost/hotel/booking/book-room/1</a></p>";

echo "<p><strong>Book Specific Room (ID 2):</strong></p>";
echo "<p><a href='http://localhost/hotel/booking/book-room/2' target='_blank' style='color: blue; text-decoration: underline;'>http://localhost/hotel/booking/book-room/2</a></p>";

echo "<p><strong>Book Specific Room (ID 6):</strong></p>";
echo "<p><a href='http://localhost/hotel/booking/book-room/6' target='_blank' style='color: blue; text-decoration: underline;'>http://localhost/hotel/booking/book-room/6</a></p>";
echo "</div>";

echo "<h3>📋 Why This Happens</h3>";
echo "<ul>";
echo "<li>CodeIgniter uses clean URLs without .php extensions</li>";
echo "<li>The .htaccess file removes the .php extension automatically</li>";
echo "<li>Your routes are configured for clean URLs</li>";
echo "<li>Adding .php breaks the routing system</li>";
echo "</ul>";

echo "<h3>🚀 Quick Test</h3>";
echo "<p>Click this link to test the correct booking URL:</p>";
echo "<p><a href='http://localhost/hotel/booking' target='_blank' style='background: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>🚀 Test Booking Page</a></p>";

echo "<h3>🔧 Alternative URLs (if clean URLs don't work)</h3>";
echo "<p>If the clean URLs still don't work, try these:</p>";
echo "<ul>";
echo "<li><a href='http://localhost/hotel/index.php/booking' target='_blank'>http://localhost/hotel/index.php/booking</a></li>";
echo "<li><a href='http://localhost/hotel/index.php/booking/select-room' target='_blank'>http://localhost/hotel/index.php/booking/select-room</a></li>";
echo "<li><a href='http://localhost/hotel/index.php/booking/book-room/1' target='_blank'>http://localhost/hotel/index.php/booking/book-room/1</a></li>";
echo "</ul>";

echo "<h3>📝 Summary</h3>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #ffc107;'>";
echo "<p><strong>❌ DON'T USE:</strong> http://localhost/hotel/booking.php</p>";
echo "<p><strong>✅ USE INSTEAD:</strong> http://localhost/hotel/booking</p>";
echo "</div>";

echo "<h3>🎯 Next Steps</h3>";
echo "<ol>";
echo "<li>Click the 'Test Booking Page' link above</li>";
echo "<li>If it works, bookmark the correct URL</li>";
echo "<li>Always use clean URLs without .php extension</li>";
echo "<li>If you still get errors, check that XAMPP Apache is running</li>";
echo "</ol>";
?> 