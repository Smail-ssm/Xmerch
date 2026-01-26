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

echo "Cleaning up Categories and Demo Data (v2)...\n";

DB::statement('SET FOREIGN_KEY_CHECKS=0;');
Childcategory::truncate();
Subcategory::truncate();
Category::truncate();
Product::truncate();
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

echo "✓ Tables truncated.\n";

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
    // Using manual instance and save() to avoid any mass assignment issues
    $category = new Category();
    $category->name = $catData['name'];
    $category->slug = $catData['slug'];
    $category->status = 1;
    $category->language_id = 1;
    $category->save();
    
    echo "Created Category: {$category->name}\n";

    foreach ($catData['subs'] as $subData) {
        $subcategory = new Subcategory();
        $subcategory->category_id = $category->id;
        $subcategory->name = $subData['name'];
        $subcategory->slug = $subData['slug'];
        $subcategory->status = 1;
        $subcategory->language_id = 1;
        $subcategory->save();
        
        echo "  + Created Subcategory: {$subcategory->name}\n";
    }
}

echo "\n✓ Category cleanup and POD structure setup complete!\n";
