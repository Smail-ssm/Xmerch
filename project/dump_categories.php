<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Childcategory;

echo "MAIN CATEGORIES:\n";
$categories = Category::all();
foreach ($categories as $cat) {
    echo "- [ID: {$cat->id}] {$cat->name} (Slug: {$cat->slug})\n";
    $subs = Subcategory::where('category_id', $cat->id)->get();
    foreach ($subs as $sub) {
        echo "  +-- [ID: {$sub->id}] {$sub->name} (Slug: {$sub->slug})\n";
        $childs = Childcategory::where('subcategory_id', $sub->id)->get();
        foreach ($childs as $child) {
            echo "      *-- [ID: {$child->id}] {$child->name}\n";
        }
    }
}
echo "\nDONE\n";
