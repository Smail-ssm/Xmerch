<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Generalsetting;
use Illuminate\Support\Facades\DB;

try {
    $gs = Generalsetting::first();
    if ($gs) {
        $gs->title = "MyBrand.ink";
        // Also update copyright or other text if needed?
        // $gs->copyright = "Copyright 2024 MyBrand.ink"; 
        $gs->save();
        echo "✓ Site title updated to: MyBrand.ink\n";
    } else {
        echo "⚠ GeneralSettings not found. Creating default...\n";
        $gs = new Generalsetting();
        $gs->title = "MyBrand.ink";
        $gs->save();
        echo "✓ Created new GeneralSettings with title: MyBrand.ink\n";
    }
} catch (\Exception $e) {
    echo "Error updating title: " . $e->getMessage() . "\n";
}
