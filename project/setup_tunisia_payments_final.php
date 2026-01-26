<?php
/**
 * Final Tunisian Payment Gateway Setup
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use Illuminate\Support\Facades\DB;

echo "===========================================\n";
echo "XMerch Tunisia Payment Gateway Setup\n";
echo "===========================================\n\n";

// Step 1: Deactivate ALL gateways first
DB::table('payment_gateways')->update([
    'checkout' => 0,
    'deposit' => 0,
    'subscription' => 0,
]);
echo "Step 1: All gateways deactivated\n";

// Step 2: Fix keywords for Tunisian gateways
DB::table('payment_gateways')->where('title', 'Flouci')->update(['keyword' => 'flouci']);
DB::table('payment_gateways')->where('title', 'Konnect')->update(['keyword' => 'konnect']);
DB::table('payment_gateways')->where('title', 'Paymee')->update(['keyword' => 'paymee']);
DB::table('payment_gateways')->where('title', 'D17')->update(['keyword' => 'd17']);
echo "Step 2: Tunisian gateway keywords fixed\n";

// Step 3: Activate COD
DB::table('payment_gateways')->where('keyword', 'cod')->update([
    'checkout' => 1,
    'deposit' => 0,
    'subscription' => 0,
]);
echo "Step 3: COD activated for checkout\n";

// Step 4: Activate Flouci (all contexts)
DB::table('payment_gateways')->where('keyword', 'flouci')->update([
    'checkout' => 1,
    'deposit' => 1,
    'subscription' => 1,
]);
echo "Step 4: Flouci activated (checkout, deposit, subscription)\n";

// Step 5: Activate Konnect (all contexts)
DB::table('payment_gateways')->where('keyword', 'konnect')->update([
    'checkout' => 1,
    'deposit' => 1,
    'subscription' => 1,
]);
echo "Step 5: Konnect activated (checkout, deposit, subscription)\n";

// Step 6: Activate Paymee (all contexts)
DB::table('payment_gateways')->where('keyword', 'paymee')->update([
    'checkout' => 1,
    'deposit' => 1,
    'subscription' => 1,
]);
echo "Step 6: Paymee activated (checkout, deposit, subscription)\n";

// Step 7: Activate D17 (checkout only - needs Ooredoo partnership)
DB::table('payment_gateways')->where('keyword', 'd17')->update([
    'checkout' => 1,
    'deposit' => 0,
    'subscription' => 0,
]);
echo "Step 7: D17 activated (checkout only)\n";

echo "\n===========================================\n";
echo "Setup Complete!\n";
echo "===========================================\n\n";

// Final status
echo "Active Payment Gateways:\n";
$active = DB::table('payment_gateways')
    ->where(function($q) {
        $q->where('checkout', 1)
          ->orWhere('deposit', 1)
          ->orWhere('subscription', 1);
    })
    ->get(['id', 'title', 'keyword', 'checkout', 'deposit', 'subscription']);

foreach ($active as $g) {
    $contexts = [];
    if ($g->checkout) $contexts[] = 'Checkout';
    if ($g->deposit) $contexts[] = 'Deposit';
    if ($g->subscription) $contexts[] = 'Subscription';
    echo "  ✓ {$g->title} ({$g->keyword}): " . implode(', ', $contexts) . "\n";
}

echo "\nDeactivated International Gateways:\n";
$inactive = DB::table('payment_gateways')
    ->where('checkout', 0)
    ->where('deposit', 0)
    ->where('subscription', 0)
    ->get(['title', 'keyword']);

foreach ($inactive as $g) {
    echo "  ✗ {$g->title} ({$g->keyword})\n";
}
