<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Disable D17 gateway since it's not implemented
$updated = DB::table('payment_gateways')
    ->where('keyword', 'd17')
    ->update(['checkout' => 0]);

if ($updated) {
    echo "✅ D17 payment gateway disabled for checkout\n";
} else {
    echo "ℹ️ D17 gateway not found or already disabled\n";
}

// Ensure COD is enabled
$updated = DB::table('payment_gateways')
    ->where('keyword', 'cod')
    ->update(['checkout' => 1]);

echo "✅ Cash on Delivery is ENABLED for checkout\n";

echo "\nActive checkout gateways:\n";
$gateways = DB::table('payment_gateways')->where('checkout', 1)->get();
foreach ($gateways as $gw) {
    echo "- {$gw->title} ({$gw->keyword})\n";
}
