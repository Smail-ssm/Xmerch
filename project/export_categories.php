<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Childcategory;

$data = [];
$categories = Category::all();
foreach ($categories as $cat) {
    $catData = [
        'id' => $cat->id,
        'name' => $cat->name,
        'slug' => $cat->slug,
        'subs' => []
    ];
    $subs = Subcategory::where('category_id', $cat->id)->get();
    foreach ($subs as $sub) {
        $subData = [
            'id' => $sub->id,
            'name' => $sub->name,
            'slug' => $sub->slug,
            'childs' => []
        ];
        $childs = Childcategory::where('subcategory_id', $sub->id)->get();
        foreach ($childs as $child) {
            $subData['childs'][] = [
                'id' => $child->id,
                'name' => $child->name
            ];
        }
        $catData['subs'][] = $subData;
    }
    $data[] = $catData;
}

file_put_contents('categories_export.json', json_encode($data, JSON_PRETTY_PRINT));
echo "Exported to categories_export.json\n";
