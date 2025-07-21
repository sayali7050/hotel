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
    
    echo "<h2>Database Connection Test</h2>";
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
    // Check if users table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✓ Users table exists</p>";
    } else {
        echo "<p style='color: red;'>✗ Users table does not exist</p>";
        exit;
    }
    
    // Get all users
    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<p>Total users: " . count($users) . "</p>";
    
    echo "<h3>All Users:</h3>";
    foreach ($users as $user) {
        echo "<p><strong>ID:</strong> {$user['id']}, <strong>Username:</strong> {$user['username']}, <strong>Email:</strong> {$user['email']}, <strong>Role:</strong> {$user['role']}, <strong>Status:</strong> {$user['status']}</p>";
    }
    
    // Check admin user specifically
    echo "<h3>Admin User Check:</h3>";
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute(['admin']);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin) {
        echo "<p style='color: green;'>✓ Admin user found</p>";
        echo "<p><strong>Admin ID:</strong> {$admin['id']}</p>";
        echo "<p><strong>Admin Role:</strong> {$admin['role']}</p>";
        echo "<p><strong>Admin Status:</strong> {$admin['status']}</p>";
        
        // Test password
        $test_password = 'admin123';
        if (password_verify($test_password, $admin['password'])) {
            echo "<p style='color: green;'>✓ Admin password is correct</p>";
        } else {
            echo "<p style='color: red;'>✗ Admin password is incorrect</p>";
            echo "<p>Password hash: " . substr($admin['password'], 0, 20) . "...</p>";
            
            // Create correct password hash
            $correct_hash = password_hash('admin123', PASSWORD_DEFAULT);
            echo "<p>Correct hash for 'admin123': " . $correct_hash . "</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ Admin user not found</p>";
        
        // Create admin user
        echo "<h3>Creating Admin User:</h3>";
        $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, first_name, last_name, role, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        try {
            $stmt->execute(['admin', 'admin@hotel.com', $admin_password, 'System', 'Administrator', 'admin', 'active']);
            echo "<p style='color: green;'>✓ Admin user created successfully</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>✗ Error creating admin user: " . $e->getMessage() . "</p>";
        }
    }
    
} catch (PDOException $e) {
    echo "<h2>Database Error</h2>";
    echo "<p style='color: red;'>✗ Database connection failed: " . $e->getMessage() . "</p>";
}
?> 