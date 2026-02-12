<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Get payment_gateways table structure
$columns = Schema::getColumnListing('payment_gateways');
echo "Payment Gateways Table Columns:\n";
print_r($columns);

echo "\n\nActive Payment Gateways:\n";
$gateways = DB::table('payment_gateways')->select('id', 'title', 'keyword', 'checkout')->get();
foreach ($gateways as $gw) {
    echo "- {$gw->title} (keyword: {$gw->keyword}, checkout: {$gw->checkout})\n";
}
