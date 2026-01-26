<?php
/**
 * Fix Tunisian Payment Gateway Keywords
 * 
 * This script ensures the keywords are correctly set for Tunisian gateways.
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\PaymentGateway;

echo "Fixing Tunisian Payment Gateway Keywords...\n\n";

// Fix keywords for Tunisian gateways
$fixes = [
    'Flouci' => 'flouci',
    'Konnect' => 'konnect',
    'Paymee' => 'paymee',
    'D17' => 'd17',
];

foreach ($fixes as $title => $keyword) {
    $gateway = PaymentGateway::where('title', $title)->first();
    if ($gateway) {
        $gateway->update(['keyword' => $keyword]);
        echo "✓ Fixed: {$title} -> keyword: {$keyword}\n";
    } else {
        echo "✗ Not found: {$title}\n";
    }
}

echo "\n--- Now running cleanup ---\n\n";

// Gateways to KEEP active for Tunisia
$tunisianGateways = [
    'cod',       // Cash on Delivery
    'flouci',    // Tunisian mobile wallet
    'konnect',   // Tunisian aggregator
    'paymee',    // Tunisian card payments
    'd17',       // Ooredoo mobile money
];

// Get all gateways again
$allGateways = PaymentGateway::all();

$deactivated = 0;
$kept = 0;

foreach ($allGateways as $gateway) {
    $keyword = $gateway->keyword ?? 'other';
    
    if (in_array($keyword, $tunisianGateways)) {
        // Keep active
        $gateway->update([
            'checkout' => 1,
            'deposit' => ($keyword != 'd17') ? 1 : 0,
            'subscription' => ($keyword != 'd17') ? 1 : 0,
        ]);
        echo "✓ ACTIVE: {$gateway->title} ({$keyword})\n";
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
echo "Active: $kept gateways\n";
echo "Deactivated: $deactivated gateways\n";
echo "\nTunisian Payment Methods Now Active:\n";
echo "  1. Cash on Delivery (COD)\n";
echo "  2. Flouci (Mobile Wallet)\n";
echo "  3. Konnect (Cards/Wallets/e-DINAR)\n";
echo "  4. Paymee (Card Payments)\n";
echo "  5. D17 (Ooredoo) - Checkout only\n";
