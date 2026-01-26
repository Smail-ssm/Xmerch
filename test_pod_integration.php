<?php
// Validates the recent changes to OrderHelper and POD integration
require __DIR__.'/project/vendor/autoload.php';
$app = require_once __DIR__.'/project/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\Order;
use App\Models\Cart;
use App\Models\PrintJob;
use App\Helpers\OrderHelper;
use Illuminate\Support\Str;

echo "--- STARTING POD INTEGRATION TEST ---\n";

// 1. Create a dummy POD product
echo "1. Creating dummy POD product...\n";
$product = new Product();
$product->name = "Test POD Shirt " . time();
$product->sku = "TEST-POD-" . time();
$product->price = 100;
$product->is_pod = 1;
$product->production_cap = 100; // Plenty of capacity
$product->print_time_minutes = 45;
$product->quality_tier = 'premium';
$product->print_file = 'test_design.png';
$product->photo = 'test.jpg';
$product->type = 'Physical';
// Required fields to avoid SQL errors
$product->slug = Str::slug($product->name);
$product->user_id = 0; // Admin
$product->save();

if(!$product->id) {
    die("FAILED: Could not create test product.\n");
}
echo "   Product created: ID {$product->id}, Name: {$product->name}\n";

// 2. Simulate Cart
echo "2. Simulating Cart...\n";
$cart = new Cart(null);
// manually constructing item structure as per Cart::add logic
$id = $product->id;
$storedItem = [
    'qty' => 2, // Ordering 2 items
    'price' => $product->price * 2,
    'item' => $product,
    'quality_tier' => 'premium', // simulating passing this
    'is_pod' => 1
];

// Cart items are keyed by id + random string usually, simpler here
$cart->items[$id] = $storedItem;
$cart->totalQty = 2;
$cart->totalPrice = 200;

// 3. Create dummy Order
echo "3. Creating dummy Order...\n";
$order = new Order();
$order->order_number = 'ORD-' . time();
$order->totalQty = 2;
$order->pay_amount = 200;
$order->customer_email = 'test@example.com';
$order->customer_name = 'Test User';
$order->customer_phone = '1234567890';
$order->customer_address = '123 Test St';
$order->customer_city = 'Test City';
$order->customer_zip = '12345';
$order->customer_country = 'Test Country';
$order->status = 'pending';
$order->save();

if(!$order->id) {
    die("FAILED: Could not create test order.\n");
}
echo "   Order created: ID {$order->id}, Ref: {$order->order_number}\n";

// 4. Test Print Job Creation using OrderHelper
echo "4. Testing OrderHelper::create_print_jobs...\n";
try {
    $count = OrderHelper::create_print_jobs($cart, $order);
    echo "   OrderHelper returned count: {$count}\n";
} catch (\Exception $e) {
    die("FAILED: OrderHelper threw exception: " . $e->getMessage() . "\n");
}

// 5. Verify Database
echo "5. Verifying Database Records...\n";
$jobs = PrintJob::where('order_id', $order->id)->get();

if($jobs->count() == 1) {
    $job = $jobs->first();
    echo "   SUCCESS: Found 1 PrintJob as expected.\n";
    echo "   - Job Status: {$job->status}\n";
    echo "   - Product ID: {$job->product_id} (Expected {$product->id})\n";
    echo "   - Quantity: {$job->quantity} (Expected 2)\n";
    echo "   - Est Time: {$job->estimated_time_minutes} min (Expected 90 - 45x2)\n";
    echo "   - Priority: {$job->priority}\n";
} else {
    echo "   FAILED: Expected 1 PrintJob, found " . $jobs->count() . "\n";
}

// 6. Cleanup
echo "6. Cleanup...\n";
// optional: delete created data to keep DB clean
// $order->delete();
// $product->delete();
// PrintJob deletes cascade from order

echo "--- TEST COMPLETE ---\n";
