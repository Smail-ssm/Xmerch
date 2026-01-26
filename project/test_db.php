<?php
// Quick database connection test
$host = '127.0.0.1';
$db = 'xmerch';
$user = 'root';
$pass = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    echo "✓ Database connection successful!\n";
    echo "Database: $db\n";
    
    // Test query
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Products table exists with {$result['count']} records\n";
    
    // Check if migrations table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'migrations'");
    if ($stmt->rowCount() > 0) {
        echo "✓ Migrations table exists\n";
    } else {
        echo "✗ Migrations table does not exist\n";
    }

    // Check if print_jobs table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'print_jobs'");
    if ($stmt->rowCount() > 0) {
        echo "✓ print_jobs table exists\n";
    } else {
        echo "✗ print_jobs table does not exist\n";
    }
    
} catch (PDOException $e) {
    echo "✗ Connection failed: " . $e->getMessage() . "\n";
    exit(1);
}
