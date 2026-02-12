<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Updating print_status default and existing orders...\n\n";

// Update default value to 'manufacturing'
try {
    DB::statement("ALTER TABLE orders MODIFY COLUMN print_status VARCHAR(255) DEFAULT 'manufacturing'");
    echo "✅ Updated default print_status to 'manufacturing'\n";
} catch (Exception $e) {
    echo "⚠️ Could not update default: " . $e->getMessage() . "\n";
}

// Update existing 'pending_print' orders to 'manufacturing' 
$updated = DB::table('orders')
    ->where('print_status', 'pending_print')
    ->where('status', 'processing')
    ->update(['print_status' => 'manufacturing']);

echo "✅ Updated {$updated} existing orders from 'pending_print' to 'manufacturing'\n";

// Show current status counts
echo "\n--- Current Print Status Distribution ---\n";
$statuses = DB::table('orders')
    ->select('print_status', DB::raw('count(*) as count'))
    ->whereNotNull('print_status')
    ->groupBy('print_status')
    ->get();

foreach ($statuses as $status) {
    echo "  {$status->print_status}: {$status->count}\n";
}

echo "\n✅ Done! Manufacturing workflow is now active.\n";
echo "\nNew Order Flow:\n";
echo "  1. Order Placed → 'manufacturing'\n";
echo "  2. Manufacturing Complete → 'print_ready' (via admin/manufacturing/queue)\n";
echo "  3. Printer Starts → 'printing'\n";
echo "  4. Printing Done → 'printed'\n";
echo "  5. Shipped → 'shipped'\n";
