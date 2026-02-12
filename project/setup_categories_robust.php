<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\Subcategory;

echo "ensuring Categories Exist...\n";

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
    $category = Category::where('slug', $catData['slug'])->first();
    
    if (!$category) {
        $category = Category::create([
            'name' => $catData['name'],
            'slug' => $catData['slug'],
            'status' => 1,
            'language_id' => 1 
        ]);
        echo "Created Category: {$category->name}\n";
    } else {
        echo "Category already exists: {$category->name}\n";
    }

    foreach ($catData['subs'] as $subData) {
        $subcategory = Subcategory::where('slug', $subData['slug'])->where('category_id', $category->id)->first();
        
        if (!$subcategory) {
            $subcategory = Subcategory::create([
                'category_id' => $category->id,
                'name' => $subData['name'],
                'slug' => $subData['slug'],
                'status' => 1,
                'language_id' => 1
            ]);
            echo "  + Created Subcategory: {$subcategory->name}\n";
        } else {
             echo "  + Subcategory already exists: {$subcategory->name}\n";
        }
    }
}

echo "\n✓ Categories verified/created.\n";
