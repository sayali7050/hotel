<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'hotel';

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Admin Password Reset</h2>";
    
    // Check if admin user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute(['admin']);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin) {
        echo "<p>✓ Admin user found</p>";
        echo "<p><strong>Current Role:</strong> {$admin['role']}</p>";
        echo "<p><strong>Current Status:</strong> {$admin['status']}</p>";
        
        // Reset admin password and ensure correct role/status
        $new_hash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ?, role = 'admin', status = 'active' WHERE username = ?");
        $stmt->execute([$new_hash, 'admin']);
        
        echo "<p style='color: green;'>✓ Admin password reset successfully!</p>";
        echo "<p><strong>New Login:</strong> admin / admin123</p>";
        
        // Verify the update
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute(['admin']);
        $updated_admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<h3>Updated Admin Details:</h3>";
        echo "<p><strong>Username:</strong> {$updated_admin['username']}</p>";
        echo "<p><strong>Role:</strong> {$updated_admin['role']}</p>";
        echo "<p><strong>Status:</strong> {$updated_admin['status']}</p>";
        
        // Test password
        if (password_verify('admin123', $updated_admin['password'])) {
            echo "<p style='color: green;'>✓ Password verification successful!</p>";
        } else {
            echo "<p style='color: red;'>✗ Password verification failed!</p>";
        }
        
    } else {
        echo "<p style='color: red;'>✗ Admin user not found!</p>";
        
        // Create admin user
        $new_hash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, first_name, last_name, role, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute(['admin', 'admin@hotel.com', $new_hash, 'System', 'Administrator', 'admin', 'active']);
        
        echo "<p style='color: green;'>✓ Admin user created successfully!</p>";
        echo "<p><strong>Login:</strong> admin / admin123</p>";
    }
    
    echo "<br><a href='auth/admin_login' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Admin Login</a>";
    
} catch (PDOException $e) {
    echo "<h2>Error</h2>";
    echo "<p style='color: red;'>✗ " . $e->getMessage() . "</p>";
}
?> 