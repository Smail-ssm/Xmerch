<?php
/**
 * Tunisian Payment Gateway Cleanup Script
 * 
 * This script deactivates international payment gateways and keeps only
 * the ones relevant for the Tunisian market.
 * 
 * Run: php cleanup_payment_gateways.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\PaymentGateway;

echo "===========================================\n";
echo "XMerch Payment Gateway Cleanup for Tunisia\n";
echo "===========================================\n\n";

// Gateways to KEEP active for Tunisia
$tunisianGateways = [
    'cod',       // Cash on Delivery - essential for Tunisia
    'flouci',    // Tunisian mobile wallet
    'konnect',   // Tunisian aggregator
    'paymee',    // Tunisian card payments
    'd17',       // Ooredoo mobile money
];

// Get all gateways
$allGateways = PaymentGateway::all();

echo "Current Payment Gateways:\n";
echo str_pad("ID", 4) . str_pad("Title", 25) . str_pad("Keyword", 15) . str_pad("Status", 10) . "\n";
echo str_repeat("-", 54) . "\n";

foreach ($allGateways as $gateway) {
    $keyword = $gateway->keyword ?? 'manual';
    $isActive = ($gateway->checkout == 1 || $gateway->deposit == 1 || $gateway->subscription == 1);
    $status = $isActive ? 'ACTIVE' : 'INACTIVE';
    
    echo str_pad($gateway->id, 4);
    echo str_pad(substr($gateway->title, 0, 24), 25);
    echo str_pad($keyword, 15);
    echo $status . "\n";
}

echo "\n-------------------------------------------\n";
echo "Gateways to KEEP for Tunisia: " . implode(', ', $tunisianGateways) . "\n";
echo "-------------------------------------------\n\n";

// Deactivate non-Tunisian gateways
$deactivated = 0;
$kept = 0;

foreach ($allGateways as $gateway) {
    $keyword = $gateway->keyword ?? 'other';
    
    if (in_array($keyword, $tunisianGateways)) {
        // Keep active
        $gateway->update([
            'checkout' => 1,
            'deposit' => ($keyword != 'd17') ? 1 : 0,  // D17 not ready for deposit
            'subscription' => ($keyword != 'd17') ? 1 : 0,
        ]);
        echo "✓ KEPT: {$gateway->title} ({$keyword})\n";
        $kept++;
    } else {
        // Deactivate
        $gateway->update([
            'checkout' => 0,
            'deposit' => 0,
            'subscription' => 0,
        ]);
        echo "✗ DEACTIVATED: {$gateway->title} ({$keyword})\n";
        $deactivated++;
    }
}

echo "\n===========================================\n";
echo "Cleanup Complete!\n";
echo "===========================================\n";
echo "Kept Active: $kept gateways\n";
echo "Deactivated: $deactivated gateways\n";
echo "\nActive payment methods for Tunisia:\n";
echo "- Cash on Delivery (COD)\n";
echo "- Flouci (Mobile Wallet)\n";
echo "- Konnect (Cards/Wallets/e-DINAR)\n";
echo "- Paymee (Card Payments)\n";
echo "- D17 (Ooredoo) - Checkout only\n";
echo "\nNote: Deactivated gateways are NOT deleted.\n";
echo "You can re-enable them from Admin Panel if needed.\n";
