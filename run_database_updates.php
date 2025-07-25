<?php
// Include CodeIgniter's database configuration
require_once 'application/config/database.php';

// Create connection using your existing config
$connection = new mysqli(
    $db['default']['hostname'],
    $db['default']['username'],
    $db['default']['password'],
    $db['default']['database']
);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

echo "Connected to database successfully.\n";

// Read the SQL file
$sql_content = file_get_contents('database_complete_update.sql');

// Split into individual statements
$statements = array_filter(
    array_map('trim', explode(';', $sql_content)),
    function($stmt) {
        return !empty($stmt) && substr($stmt, 0, 2) !== '--';
    }
);

echo "Executing " . count($statements) . " database statements...\n\n";

$success = 0;
$errors = 0;

foreach ($statements as $statement) {
    if ($connection->query($statement)) {
        $success++;
        echo "✓ " . substr($statement, 0, 60) . "...\n";
    } else {
        $errors++;
        echo "✗ Error: " . $connection->error . "\n";
        echo "  Statement: " . substr($statement, 0, 100) . "...\n";
    }
}

echo "\n=== Summary ===\n";
echo "Successful: $success\n";
echo "Errors: $errors\n";

if ($errors === 0) {
    echo "All database updates completed successfully!\n";
} else {
    echo "Some errors occurred. Please review the output above.\n";
}

$connection->close();
?>