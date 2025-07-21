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
    
    echo "<h2>Login Debug Information</h2>";
    
    // Test admin login credentials
    $test_username = 'admin';
    $test_password = 'admin123';
    
    echo "<h3>Testing Admin Login: {$test_username} / {$test_password}</h3>";
    
    // Step 1: Find user by username
    echo "<h4>Step 1: Finding user by username</h4>";
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$test_username]);
    $user_by_username = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user_by_username) {
        echo "<p style='color: green;'>✓ User found by username</p>";
        echo "<p><strong>User ID:</strong> {$user_by_username['id']}</p>";
        echo "<p><strong>Username:</strong> {$user_by_username['username']}</p>";
        echo "<p><strong>Email:</strong> {$user_by_username['email']}</p>";
        echo "<p><strong>Role:</strong> {$user_by_username['role']}</p>";
        echo "<p><strong>Status:</strong> {$user_by_username['status']}</p>";
        echo "<p><strong>Password Hash:</strong> " . substr($user_by_username['password'], 0, 20) . "...</p>";
    } else {
        echo "<p style='color: red;'>✗ User not found by username</p>";
    }
    
    // Step 2: Check if user is active
    echo "<h4>Step 2: Checking user status</h4>";
    if ($user_by_username && $user_by_username['status'] == 'active') {
        echo "<p style='color: green;'>✓ User status is active</p>";
    } elseif ($user_by_username) {
        echo "<p style='color: red;'>✗ User status is: {$user_by_username['status']}</p>";
    } else {
        echo "<p style='color: red;'>✗ Cannot check status - user not found</p>";
    }
    
    // Step 3: Check if user role is admin
    echo "<h4>Step 3: Checking user role</h4>";
    if ($user_by_username && $user_by_username['role'] == 'admin') {
        echo "<p style='color: green;'>✓ User role is admin</p>";
    } elseif ($user_by_username) {
        echo "<p style='color: red;'>✗ User role is: {$user_by_username['role']}</p>";
    } else {
        echo "<p style='color: red;'>✗ Cannot check role - user not found</p>";
    }
    
    // Step 4: Test password verification
    echo "<h4>Step 4: Testing password verification</h4>";
    if ($user_by_username) {
        if (password_verify($test_password, $user_by_username['password'])) {
            echo "<p style='color: green;'>✓ Password verification successful</p>";
        } else {
            echo "<p style='color: red;'>✗ Password verification failed</p>";
            
            // Show what the correct hash should be
            $correct_hash = password_hash($test_password, PASSWORD_DEFAULT);
            echo "<p><strong>Expected hash for '{$test_password}':</strong> {$correct_hash}</p>";
            echo "<p><strong>Current hash in database:</strong> {$user_by_username['password']}</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ Cannot test password - user not found</p>";
    }
    
    // Step 5: Simulate the exact login process
    echo "<h4>Step 5: Simulating login process</h4>";
    if ($user_by_username && $user_by_username['status'] == 'active' && password_verify($test_password, $user_by_username['password'])) {
        if ($user_by_username['role'] == 'admin') {
            echo "<p style='color: green;'>✓ Login simulation successful - Admin access granted</p>";
            echo "<p><strong>Session data that would be set:</strong></p>";
            echo "<ul>";
            echo "<li>user_id: {$user_by_username['id']}</li>";
            echo "<li>username: {$user_by_username['username']}</li>";
            echo "<li>email: {$user_by_username['email']}</li>";
            echo "<li>role: {$user_by_username['role']}</li>";
            echo "<li>logged_in: TRUE</li>";
            echo "</ul>";
        } else {
            echo "<p style='color: red;'>✗ Login simulation failed - User is not admin</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ Login simulation failed</p>";
        
        if (!$user_by_username) {
            echo "<p>Reason: User not found</p>";
        } elseif ($user_by_username['status'] != 'active') {
            echo "<p>Reason: User status is not active</p>";
        } elseif (!password_verify($test_password, $user_by_username['password'])) {
            echo "<p>Reason: Password verification failed</p>";
        }
    }
    
    // Step 6: Show all users in database
    echo "<h4>Step 6: All users in database</h4>";
    $stmt = $pdo->query("SELECT id, username, email, role, status FROM users ORDER BY id");
    $all_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($all_users) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Status</th></tr>";
        foreach ($all_users as $user) {
            $row_color = ($user['username'] == 'admin') ? 'background-color: #e8f5e8;' : '';
            echo "<tr style='{$row_color}'>";
            echo "<td>{$user['id']}</td>";
            echo "<td>{$user['username']}</td>";
            echo "<td>{$user['email']}</td>";
            echo "<td>{$user['role']}</td>";
            echo "<td>{$user['status']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: red;'>No users found in database</p>";
    }
    
    echo "<br><a href='reset_admin.php' style='background: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>Reset Admin Password</a>";
    echo "<a href='auth/admin_login' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Admin Login</a>";
    
} catch (PDOException $e) {
    echo "<h2>Database Error</h2>";
    echo "<p style='color: red;'>✗ " . $e->getMessage() . "</p>";
}
?> 