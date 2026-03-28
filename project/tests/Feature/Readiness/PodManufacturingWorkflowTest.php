<?php

namespace Tests\Feature\Readiness;

use App\Helpers\OrderHelper;
use App\Models\Order;
use App\Models\PrintJob;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class PodManufacturingWorkflowTest extends TestCase
{
    use DatabaseTransactions;

    public function test_create_print_jobs_is_idempotent_and_moves_order_to_manufacturing()
    {
        $categoryId = DB::table('categories')->value('id');
        if (!$categoryId) {
            $this->markTestSkipped('Categories are required for product creation.');
        }

        $product = Product::create([
            'user_id' => 0,
            'category_id' => $categoryId,
            'name' => 'POD Test Product ' . Str::random(6),
            'photo' => 'placeholder.png',
            'price' => 25,
            'type' => 'Physical',
            'status' => 1,
            'slug' => 'pod-test-' . Str::lower(Str::random(8)),
            'stock' => 0,
            'stock_check' => 0,
            'is_pod' => 1,
            'production_cap' => 5,
            'print_file' => 'print-file.png',
            'print_time_minutes' => 30,
            'quality_tier' => 'standard',
        ]);

        $cartPayload = [
            'totalQty' => 1,
            'totalPrice' => 25,
            'items' => [
                [
                    'qty' => 1,
                    'item' => [
                        'id' => $product->id,
                    ],
                ],
            ],
        ];

        $order = Order::create([
            'user_id' => null,
            'cart' => json_encode($cartPayload),
            'status' => 'processing',
            'payment_status' => 'Completed',
            'order_number' => 'ORD-' . Str::upper(Str::random(10)),
            'totalQty' => '1',
            'pay_amount' => 25,
            'customer_email' => 'buyer_' . Str::random(8) . '@example.com',
            'customer_name' => 'Buyer Test',
            'customer_country' => 'Tunisia',
            'customer_phone' => '20123456',
            'currency_sign' => 'TND',
            'currency_name' => 'TND',
            'currency_value' => 1,
            'shipping_cost' => 0,
            'tax' => 0,
            'method' => 'Cash On Delivery',
            'print_status' => Order::PRINT_STATUS_PENDING,
        ]);

        $cartObject = (object) [
            'items' => [
                [
                    'qty' => 1,
                    'item' => [
                        'id' => $product->id,
                    ],
                ],
            ],
        ];

        OrderHelper::create_print_jobs($cartObject, $order);
        OrderHelper::create_print_jobs($cartObject, $order);

        $this->assertSame(
            1,
            PrintJob::where('order_id', $order->id)->where('product_id', $product->id)->count()
        );

        $order->refresh();
        $this->assertSame(Order::PRINT_STATUS_MANUFACTURING, $order->print_status);
    }
}

