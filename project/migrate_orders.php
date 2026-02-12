<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Migrating ALL 'pending_print' orders to 'manufacturing'...\n\n";

// Update ALL pending_print orders to manufacturing (not just processing ones)
$updated = DB::table('orders')
    ->where('print_status', 'pending_print')
    ->update(['print_status' => 'manufacturing']);

echo "✅ Updated {$updated} orders from 'pending_print' to 'manufacturing'\n";

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

echo "\n✅ Done!\n";
