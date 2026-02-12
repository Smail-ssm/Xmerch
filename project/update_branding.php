<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Generalsetting;

try {
    $gs = Generalsetting::first();
    if ($gs) {
        $gs->title = "We-Brand.shop";
        $gs->save();
        echo "✓ Site title updated to: We-Brand.shop\n";
    } else {
        $gs = new Generalsetting();
        $gs->title = "We-Brand.shop";
        $gs->save();
        echo "✓ Created new GeneralSettings with title: We-Brand.shop\n";
    }
} catch (\Exception $e) {
    echo "Error updating title: " . $e->getMessage() . "\n";
}
