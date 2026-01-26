<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Childcategory;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

echo "Cleaning up Categories and Demo Data...\n";

// 1. Clear existing category hierarchy
DB::statement('SET FOREIGN_KEY_CHECKS=0;');
Childcategory::truncate();
Subcategory::truncate();
Category::truncate();
// Also clear products as they are demo data and linked to old categories
Product::truncate();
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

echo "✓ Categories, Subcategories, Childcategories, and Products truncated.\n";

$newCategories = [
    [
        'name' => 'Apparel',
        'slug' => 'apparel',
        'subs' => [
            ['name' => 'T-Shirts', 'slug' => 't-shirts'],
            ['name' => 'Hoodies', 'slug' => 'hoodies'],
            ['name' => 'Sweatshirts', 'slug' => 'sweatshirts'],
            ['name' => 'Tank Tops', 'slug' => 'tank-tops'],
        ]
    ],
    [
        'name' => 'Home & Living',
        'slug' => 'home-living',
        'subs' => [
            ['name' => 'Mugs', 'slug' => 'mugs'],
            ['name' => 'Pillows', 'slug' => 'pillows'],
            ['name' => 'Canvas Prints', 'slug' => 'canvas-prints'],
            ['name' => 'Posters', 'slug' => 'posters'],
        ]
    ],
    [
        'name' => 'Accessories',
        'slug' => 'accessories',
        'subs' => [
            ['name' => 'Tote Bags', 'slug' => 'tote-bags'],
            ['name' => 'Phone Cases', 'slug' => 'phone-cases'],
            ['name' => 'Stickers', 'slug' => 'stickers'],
            ['name' => 'Notebooks', 'slug' => 'notebooks'],
        ]
    ],
    [
        'name' => 'Tech Accessories',
        'slug' => 'tech-accessories',
        'subs' => [
            ['name' => 'Laptop Sleeves', 'slug' => 'laptop-sleeves'],
            ['name' => 'Mouse Pads', 'slug' => 'mouse-pads'],
        ]
    ]
];

foreach ($newCategories as $catData) {
    $category = Category::create([
        'name' => $catData['name'],
        'slug' => $catData['slug'],
        'status' => 1,
        'language_id' => 1 // Assuming 1 is default
    ]);
    echo "Created Category: {$category->name}\n";

    foreach ($catData['subs'] as $subData) {
        $subcategory = Subcategory::create([
            'category_id' => $category->id,
            'name' => $subData['name'],
            'slug' => $subData['slug'],
            'status' => 1
        ]);
        echo "  + Created Subcategory: {$subcategory->name}\n";
    }
}

echo "\n✓ Category cleanup and POD structure setup complete!\n";
