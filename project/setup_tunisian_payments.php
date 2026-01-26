<?php
/**
 * Tunisian Payment Gateways Setup Script
 * 
 * Run this script to add Flouci, Konnect, Paymee, and D17 to your payment_gateways table.
 * After running, go to Admin > Payment Settings to configure each gateway.
 * 
 * Usage: php setup_tunisian_payments.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\PaymentGateway;
use App\Models\Currency;

// Get TND currency ID
$tnd = Currency::where('name', 'TND')->first();
$currencyId = $tnd ? '["'.$tnd->id.'"]' : '*';

echo "Setting up Tunisian Payment Gateways...\n";
echo "Currency ID: $currencyId\n\n";

$gateways = [
    [
        'title' => 'Flouci',
        'subtitle' => 'Pay with Flouci Mobile Wallet',
        'name' => 'Flouci',
        'type' => 'automatic',
        'keyword' => 'flouci',
        'information' => json_encode([
            'app_token' => 'YOUR_FLOUCI_APP_TOKEN',      // TODO: Fill this
            'app_secret' => 'YOUR_FLOUCI_APP_SECRET',    // TODO: Fill this
            'sandbox_check' => 1,                         // 1 = sandbox, 0 = live
            'text' => 'Pay securely with Flouci mobile wallet'
        ]),
        'details' => 'Flouci is a popular Tunisian mobile payment solution. Get your API keys at https://flouci.com/developers',
        'currency_id' => $currencyId,
        'checkout' => 1,
        'deposit' => 1,
        'subscription' => 1,
    ],
    [
        'title' => 'Konnect',
        'subtitle' => 'Cards, Wallets & e-DINAR',
        'name' => 'Konnect',
        'type' => 'automatic',
        'keyword' => 'konnect',
        'information' => json_encode([
            'api_key' => 'YOUR_KONNECT_API_KEY',         // TODO: Fill this
            'wallet_id' => 'YOUR_KONNECT_WALLET_ID',     // TODO: Fill this
            'sandbox_check' => 1,
            'text' => 'Pay with card, wallet, or e-DINAR via Konnect'
        ]),
        'details' => 'Konnect is a payment aggregator supporting multiple methods. Register at https://konnect.network',
        'currency_id' => $currencyId,
        'checkout' => 1,
        'deposit' => 1,
        'subscription' => 1,
    ],
    [
        'title' => 'Paymee',
        'subtitle' => 'Card & Bank Payments',
        'name' => 'Paymee',
        'type' => 'automatic',
        'keyword' => 'paymee',
        'information' => json_encode([
            'api_key' => 'YOUR_PAYMEE_API_KEY',          // TODO: Fill this
            'sandbox_check' => 1,
            'text' => 'Pay with credit/debit card via Paymee'
        ]),
        'details' => 'Paymee is a Tunisian payment gateway for card transactions. Get API at https://paymee.tn',
        'currency_id' => $currencyId,
        'checkout' => 1,
        'deposit' => 1,
        'subscription' => 1,
    ],
    [
        'title' => 'D17',
        'subtitle' => 'Ooredoo Mobile Money',
        'name' => 'D17',
        'type' => 'automatic',
        'keyword' => 'd17',
        'information' => json_encode([
            'merchant_id' => 'YOUR_D17_MERCHANT_ID',     // TODO: Fill this
            'api_key' => 'YOUR_D17_API_KEY',             // TODO: Fill this
            'sandbox_check' => 1,
            'text' => 'Pay with D17 (Ooredoo) mobile money'
        ]),
        'details' => 'D17 is Ooredoo Tunisia mobile money service. Contact Ooredoo for merchant access.',
        'currency_id' => $currencyId,
        'checkout' => 1,
        'deposit' => 0,
        'subscription' => 0,
    ],
];

foreach ($gateways as $gateway) {
    $existing = PaymentGateway::where('keyword', $gateway['keyword'])->first();
    
    if ($existing) {
        echo "✓ {$gateway['title']} already exists (ID: {$existing->id})\n";
    } else {
        $new = PaymentGateway::create($gateway);
        echo "✓ Created {$gateway['title']} (ID: {$new->id})\n";
    }
}

echo "\n========================================\n";
echo "Setup Complete!\n";
echo "========================================\n";
echo "\nNext Steps:\n";
echo "1. Go to Admin Panel > Payment Settings\n";
echo "2. Find each gateway and click Edit\n";
echo "3. Enter your API credentials\n";
echo "4. Set sandbox_check to 0 for live mode\n";
echo "5. Enable/disable for checkout, deposit, subscription as needed\n";
echo "\nAPI Registration Links:\n";
echo "- Flouci: https://flouci.com/developers\n";
echo "- Konnect: https://konnect.network\n";
echo "- Paymee: https://paymee.tn\n";
echo "- D17: Contact Ooredoo Tunisia\n";
