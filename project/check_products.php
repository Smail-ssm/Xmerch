<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

$products = Product::select('id', 'name', 'category_id')->get();
foreach ($products as $product) {
    echo "- [ID: {$product->id}] {$product->name} (Cat ID: {$product->category_id})\n";
}
