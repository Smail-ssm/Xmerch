<?php
/**
 * Tunisia Setup Script Executor
 * Runs the Tunisia configuration SQL script
 */

$host = '127.0.0.1';
$db = 'xmerch';
$user = 'root';
$pass = '';

try {
    echo "🇹🇳 Tunisia Setup Script\n";
    echo "========================\n\n";
    
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ Connected to database: $db\n\n";
    
    // Read SQL file
    $sql = file_get_contents(__DIR__ . '/tunisia_setup.sql');
    
    // Remove comments and split by semicolon
    $sql = preg_replace('/--.*$/m', '', $sql); // Remove single-line comments
    $sql = preg_replace('/\/\*.*?\*\//s', '', $sql); // Remove multi-line comments
    
    // Split into individual statements
    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        function($stmt) { return !empty($stmt); }
    );
    
    echo "📝 Executing " . count($statements) . " SQL statements...\n\n";
    
    $success = 0;
    $errors = 0;
    
    foreach ($statements as $index => $statement) {
        try {
            $pdo->exec($statement);
            $success++;
            
            // Show progress for important operations
            if (stripos($statement, 'UPDATE generalsettings') !== false) {
                echo "✓ Updated general settings\n";
            } elseif (stripos($statement, 'UPDATE payment_gateways') !== false && stripos($statement, 'cod') !== false) {
                echo "✓ Enabled Cash on Delivery\n";
            } elseif (stripos($statement, 'INSERT INTO shippings') !== false) {
                echo "✓ Added Tunisia shipping zones\n";
            } elseif (stripos($statement, 'INSERT INTO payment_gateways') !== false) {
                if (stripos($statement, 'paymee') !== false) echo "✓ Added Paymee gateway\n";
                if (stripos($statement, 'konnect') !== false) echo "✓ Added Konnect gateway\n";
                if (stripos($statement, 'edinar') !== false) echo "✓ Added e-Dinar gateway\n";
                if (stripos($statement, 'flouci') !== false) echo "✓ Added Flouci gateway\n";
            } elseif (stripos($statement, 'INSERT INTO languages') !== false) {
                if (stripos($statement, 'Français') !== false) echo "✓ Added French language\n";
                if (stripos($statement, 'العربية') !== false) echo "✓ Added Arabic language\n";
            } elseif (stripos($statement, 'INSERT INTO pickups') !== false) {
                echo "✓ Added pickup locations\n";
            }
        } catch (PDOException $e) {
            $errors++;
            // Only show error if it's not a duplicate entry (which is expected)
            if (strpos($e->getMessage(), 'Duplicate entry') === false) {
                echo "✗ Error: " . $e->getMessage() . "\n";
            }
        }
    }
    
    echo "\n========================\n";
    echo "✓ Setup Complete!\n";
    echo "  Successful: $success statements\n";
    if ($errors > 0) {
        echo "  Skipped: $errors statements (likely duplicates)\n";
    }
    echo "\n";
    
    // Verify configuration
    echo "📊 Verification:\n";
    echo "========================\n";
    
    // Check currency
    $stmt = $pdo->query("SELECT currency_code, currency_sign FROM generalsettings LIMIT 1");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Currency: {$result['currency_code']} ({$result['currency_sign']})\n";
    
    // Check COD status
    $stmt = $pdo->query("SELECT status FROM payment_gateways WHERE keyword = 'cod' LIMIT 1");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Cash on Delivery: " . ($result['status'] ? 'Enabled ✓' : 'Disabled') . "\n";
    
    // Count shipping zones
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM shippings");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Shipping Zones: {$result['count']}\n";
    
    // Count payment gateways
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM payment_gateways");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Payment Gateways: {$result['count']}\n";
    
    // Check languages
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM languages");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Languages: {$result['count']}\n";
    
    echo "\n✅ Tunisia configuration applied successfully!\n";
    echo "\nNext steps:\n";
    echo "1. Update .env file (APP_TIMEZONE, APP_LOCALE)\n";
    echo "2. Run: php artisan config:clear\n";
    echo "3. Run: php artisan cache:clear\n";
    echo "4. Configure payment gateway credentials in admin panel\n";
    
} catch (PDOException $e) {
    echo "✗ Database Error: " . $e->getMessage() . "\n";
    exit(1);
}
