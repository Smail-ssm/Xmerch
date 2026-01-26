<?php
require __DIR__.'/project/vendor/autoload.php';

use Carbon\Carbon;

try {
    echo "Testing Carbon...\n";
    $date = Carbon::now()->subDays(30);
    echo "Date: " . $date . "\n";
    echo "Success!\n";
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
