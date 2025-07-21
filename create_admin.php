<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'hotel';

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Setting up Hotel Management System</h2>";
    
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database`");
    echo "<p style='color: green;'>✓ Database '$database' created/verified</p>";
    
    // Connect to the hotel database
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create users table
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        first_name VARCHAR(50) NOT NULL,
        last_name VARCHAR(50) NOT NULL,
        phone VARCHAR(20),
        address TEXT,
        role ENUM('admin', 'staff', 'customer') NOT NULL DEFAULT 'customer',
        status ENUM('active', 'inactive', 'pending') NOT NULL DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "<p style='color: green;'>✓ Users table created</p>";
    
    // Create rooms table
    $sql = "CREATE TABLE IF NOT EXISTS rooms (
        id INT PRIMARY KEY AUTO_INCREMENT,
        room_number VARCHAR(10) UNIQUE NOT NULL,
        room_type VARCHAR(50) NOT NULL,
        capacity INT NOT NULL,
        price_per_night DECIMAL(10,2) NOT NULL,
        description TEXT,
        amenities TEXT,
        status ENUM('available', 'occupied', 'maintenance', 'reserved') DEFAULT 'available',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "<p style='color: green;'>✓ Rooms table created</p>";
    
    // Create bookings table
    $sql = "CREATE TABLE IF NOT EXISTS bookings (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        room_id INT NOT NULL,
        check_in_date DATE NOT NULL,
        check_out_date DATE NOT NULL,
        total_amount DECIMAL(10,2) NOT NULL,
        status ENUM('pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled') DEFAULT 'pending',
        special_requests TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);
    echo "<p style='color: green;'>✓ Bookings table created</p>";
    
    // Create payments table
    $sql = "CREATE TABLE IF NOT EXISTS payments (
        id INT PRIMARY KEY AUTO_INCREMENT,
        booking_id INT NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        payment_method ENUM('cash', 'credit_card', 'debit_card', 'online') NOT NULL,
        payment_status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
        transaction_id VARCHAR(100),
        payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);
    echo "<p style='color: green;'>✓ Payments table created</p>";
    
    // Create staff_assignments table
    $sql = "CREATE TABLE IF NOT EXISTS staff_assignments (
        id INT PRIMARY KEY AUTO_INCREMENT,
        staff_id INT NOT NULL,
        department VARCHAR(50) NOT NULL,
        position VARCHAR(50) NOT NULL,
        salary DECIMAL(10,2),
        hire_date DATE NOT NULL,
        status ENUM('active', 'inactive', 'terminated') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (staff_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);
    echo "<p style='color: green;'>✓ Staff assignments table created</p>";
    
    // Check if admin user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute(['admin']);
    
    if (!$stmt->fetch()) {
        // Create admin user
        $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, first_name, last_name, role, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute(['admin', 'admin@hotel.com', $admin_password, 'System', 'Administrator', 'admin', 'active']);
        echo "<p style='color: green;'>✓ Admin user created successfully</p>";
    } else {
        echo "<p style='color: blue;'>ℹ Admin user already exists</p>";
    }
    
    // Insert sample rooms
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM rooms");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $rooms = [
            ['101', 'Standard Single', 1, 80.00, 'Comfortable single room with basic amenities', 'WiFi, TV, AC, Private Bathroom'],
            ['102', 'Standard Double', 2, 120.00, 'Spacious double room with city view', 'WiFi, TV, AC, Private Bathroom, Mini Fridge'],
            ['201', 'Deluxe Suite', 4, 200.00, 'Luxury suite with separate living area', 'WiFi, TV, AC, Private Bathroom, Mini Bar, Room Service'],
            ['202', 'Executive Suite', 2, 180.00, 'Executive suite with business amenities', 'WiFi, TV, AC, Private Bathroom, Work Desk, Coffee Maker'],
            ['301', 'Presidential Suite', 6, 500.00, 'Ultimate luxury with premium services', 'WiFi, TV, AC, Private Bathroom, Jacuzzi, Butler Service, Premium View']
        ];
        
        $stmt = $pdo->prepare("INSERT INTO rooms (room_number, room_type, capacity, price_per_night, description, amenities) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($rooms as $room) {
            $stmt->execute($room);
        }
        echo "<p style='color: green;'>✓ Sample rooms created</p>";
    } else {
        echo "<p style='color: blue;'>ℹ Rooms already exist</p>";
    }
    
    // Insert sample staff
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'staff'");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $staff_password = password_hash('admin123', PASSWORD_DEFAULT);
        $staff_users = [
            ['staff1', 'staff1@hotel.com', $staff_password, 'John', 'Doe', '+1234567890', 'staff'],
            ['staff2', 'staff2@hotel.com', $staff_password, 'Jane', 'Smith', '+1234567891', 'staff']
        ];
        
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, first_name, last_name, phone, role, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'active')");
        foreach ($staff_users as $staff) {
            $stmt->execute($staff);
        }
        
        // Add staff assignments
        $stmt = $pdo->prepare("INSERT INTO staff_assignments (staff_id, department, position, salary, hire_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([2, 'Front Desk', 'Receptionist', 2500.00, '2024-01-15']);
        $stmt->execute([3, 'Housekeeping', 'Supervisor', 2800.00, '2024-02-01']);
        
        echo "<p style='color: green;'>✓ Sample staff created</p>";
    } else {
        echo "<p style='color: blue;'>ℹ Staff already exist</p>";
    }
    
    echo "<h3 style='color: green;'>✓ Setup Complete!</h3>";
    echo "<p><strong>Admin Login:</strong> admin / admin123</p>";
    echo "<p><strong>Staff Login:</strong> staff1 / admin123 or staff2 / admin123</p>";
    echo "<p><a href='auth/admin_login' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Admin Login</a></p>";
    
} catch (PDOException $e) {
    echo "<h2>Error</h2>";
    echo "<p style='color: red;'>✗ " . $e->getMessage() . "</p>";
}
?> 