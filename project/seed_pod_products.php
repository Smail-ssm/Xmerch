<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Str;

echo "Seeding Tunisia POD Products...\n";

// Fetch Categories created in previous step
$apparel = Category::where('slug', 'apparel')->first();
$home = Category::where('slug', 'home-living')->first();
$acc = Category::where('slug', 'accessories')->first();

if (!$apparel) { die("Error: 'apparel' category not found. Run cleanup_categories_final.php first.\n"); }

$tshirt = Subcategory::where('slug', 't-shirts')->first()->id;
$hoodie = Subcategory::where('slug', 'hoodies')->first()->id;
$mug = Subcategory::where('slug', 'mugs')->first()->id;
$bag = Subcategory::where('slug', 'tote-bags')->first()->id;

$products = [
    [
        'name' => 'Tunisia Heritage T-Shirt',
        'slug' => 'tunisia-heritage-tshirt',
        'category_id' => $apparel->id,
        'subcategory_id' => $tshirt,
        'price' => 45.000,
        'previous_price' => 55.000,
        'details' => '<p>Premium lots cotton T-shirt featuring traditional Tunisian calligraphy.</p>',
        'photo' => 'assets/images/noimage.png', // Placeholder
        'type' => 'Physical',
        'stock' => 100
    ],
    [
        'name' => 'Carthage Eagle Hoodie',
        'slug' => 'carthage-eagle-hoodie',
        'category_id' => $apparel->id,
        'subcategory_id' => $hoodie,
        'price' => 89.900,
        'previous_price' => 110.000,
        'details' => '<p>Warm and cozy hoodie with the majestic Eagle of Carthage emblem.</p>',
        'photo' => 'assets/images/noimage.png',
        'type' => 'Physical',
        'stock' => 50
    ],
    [
        'name' => 'Sidi Bou Said Ceramic Mug',
        'slug' => 'sidi-bou-said-mug',
        'category_id' => $home->id,
        'subcategory_id' => $mug,
        'price' => 25.500,
        'previous_price' => 0,
        'details' => '<p>Ceramic mug inspired by the blue and white architecture of Sidi Bou Said.</p>',
        'photo' => 'assets/images/noimage.png',
        'type' => 'Physical',
        'stock' => 200
    ],
    [
        'name' => 'Desert Rose Tote Bag',
        'slug' => 'desert-rose-tote',
        'category_id' => $acc->id,
        'subcategory_id' => $bag,
        'price' => 35.000,
        'previous_price' => 0,
        'details' => '<p>Eco-friendly cotton tote bag with a desert rose artistic print.</p>',
        'photo' => 'assets/images/noimage.png',
        'type' => 'Physical',
        'stock' => 150
    ],
    [
        'name' => 'Tunis Skyline Art Print',
        'slug' => 'tunis-skyline-print',
        'category_id' => $home->id,
        'subcategory_id' => $mug, // mapping to mug/home for now as poster subcat ID lookup wasn't explicit above
        'price' => 60.000,
        'previous_price' => 75.000,
        'details' => '<p>High-quality canvas print of the Tunis city skyline at sunset.</p>',
        'photo' => 'assets/images/noimage.png',
        'type' => 'Physical',
        'stock' => 20
    ]
];

foreach ($products as $p) {
    // Check if exists
    if(Product::where('slug', $p['slug'])->exists()) {
        echo "Skipping {$p['name']} (Already exists)\n";
        continue;
    }

    $prod = new Product();
    $prod->fill($p);
    $prod->sku = Str::random(8);
    $prod->user_id = 0; // Admin product
    $prod->status = 1;
    $prod->is_discount = ($p['previous_price'] > 0) ? 1 : 0;
    $prod->save();
    echo "Created: {$p['name']} ({$p['price']} TND)\n";
}

echo "\n✓ POD Product Seeding Complete for Tunisia Market.\n";
