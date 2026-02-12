<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\Role;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class ManufacturingDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Setup Roles
        $roles = [
            'Printer Staff' => 'print_production',
            'Manufacturing Staff' => 'manufacturing',
            'Operations Manager' => 'print_production , manufacturing'
        ];

        $roleIds = [];

        foreach ($roles as $name => $sections) {
            $role = Role::firstOrCreate(
                ['name' => $name],
                ['section' => $sections]
            );
            // Ensure sections are updated if role exists
            $role->update(['section' => $sections]);
            $roleIds[$name] = $role->id;
        }

        // 2. Setup Admins
        $admins = [
            [
                'name' => 'Printer User',
                'email' => 'printer@test.com',
                'password' => '1234',
                'role' => 'Printer Staff'
            ],
            [
                'name' => 'Manufacturing User',
                'email' => 'manufacturing@test.com',
                'password' => '1234',
                'role' => 'Manufacturing Staff'
            ],
            [
                'name' => 'All-In-One User',
                'email' => 'allinone@test.com',
                'password' => '1234',
                'role' => 'Operations Manager'
            ]
        ];

        foreach ($admins as $adminData) {
            // Check if exists
            $admin = Admin::where('email', $adminData['email'])->first();
            if (!$admin) {
                $admin = new Admin();
                $admin->email = $adminData['email'];
            }
            $admin->name = $adminData['name'];
            $admin->password = Hash::make($adminData['password']);
            $admin->role_id = $roleIds[$adminData['role']];
            $admin->phone = '1234567890';
            $admin->save();
        }

        // 3. Ensure we have users (customers)
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'John Doe',
                'email' => 'customer@example.com',
                'password' => Hash::make('1234'),
            ]);
        }

        // 4. Ensure we have products
        $productIds = [];
        $existingProducts = Product::limit(10)->get();
        
        if ($existingProducts->count() < 5) {
            for ($i = 1; $i <= 10; $i++) {
                $prod = Product::create([
                    'name' => 'Test Product ' . $i,
                    'slug' => 'test-product-' . $i,
                    'sku' => 'SKU-' . $i,
                    'photo' => 'placeholder.png', // Assuming a placeholder exists or handle in UI
                    'price' => rand(10, 100),
                    'type' => 'Physical',
                    'status' => 1
                ]);
                $productIds[] = $prod->id;
            }
        } else {
            $productIds = $existingProducts->pluck('id')->toArray();
        }

        // 5. Generate Orders
        // We want a mix of dates for analytics testing
        // Current Month Data
        $this->createOrdersForPeriod(now()->startOfMonth(), now()->endOfMonth(), 30, $productIds, $user->id);
        
        // Last Month Data
        $this->createOrdersForPeriod(now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth(), 20, $productIds, $user->id);

        $this->command->info('Manufacturing and Printer data seeded successfully!');
        $this->command->info('Admins created:');
        $this->command->info(' - printer@test.com / 1234');
        $this->command->info(' - manufacturing@test.com / 1234');
        $this->command->info(' - allinone@test.com / 1234');
    }

    private function createOrdersForPeriod($start, $end, $count, $productIds, $userId)
    {
        for ($i = 0; $i < $count; $i++) {
            $date = Carbon::createFromTimestamp(rand($start->timestamp, $end->timestamp));
            
            // Randomly determine status
            // 60% Printed (Completed for Manufacturing)
            // 30% Pending Print
            // 10% Other
            $rand = rand(1, 100);
            
            $status = 'pending';
            $printStatus = 0; // Pending
            $printedAt = null;

            if ($rand <= 60) {
                // Printed
                $printStatus = 1;
                $printedAt = $date->copy()->addHours(rand(1, 48)); // Printed shortly after order
                $status = 'processing';
            } elseif ($rand <= 90) {
                // Pending Print
                $printStatus = 0;
                $status = 'pending';
            } else {
                // Completed/Shipped
                $printStatus = 1;
                $printedAt = $date->copy()->addHours(rand(1, 24));
                $status = 'completed';
            }

            // Build Cart
            $cartItems = [];
            $totalQty = 0;
            $itemsCount = rand(1, 3);
            
            for ($j = 0; $j < $itemsCount; $j++) {
                $pid = $productIds[array_rand($productIds)];
                $qty = rand(1, 5);
                $product = Product::find($pid);
                
                // Structure mocking the Shopping Cart format used in controller
                $cartItems[$pid] = [
                    'qty' => $qty,
                    'price' => $product->price,
                    'item' => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'photo' => $product->photo,
                        'price' => $product->price,
                    ]
                ];
                $totalQty += $qty;
            }

            $cart = [
                'items' => $cartItems,
                'totalQty' => $totalQty,
                'totalPrice' => 100 // Dummy
            ];

            Order::create([
                'order_number' => strtoupper(uniqid('ORD-')),
                'user_id' => $userId,
                'cart' => json_encode($cart),
                'totalQty' => $totalQty,
                'pay_amount' => 100,
                'status' => $status,
                'print_status' => $printStatus,
                'printed_at' => $printedAt,
                'created_at' => $date,
                'updated_at' => $date,
                'customer_email' => 'customer@example.com',
                'customer_name' => 'John Doe',
                'customer_phone' => '123456789',
                'customer_address' => '123 Test St',
                'customer_city' => 'Test City',
                'customer_zip' => '12345',
                'customer_country' => 'Testland',
                'shipping' => 'Standard',
                'payment_method' => 'Stripe',
                'txnid' => 'txn_' . uniqid(),
                'charge_id' => 'ch_' . uniqid(),
                'payment_status' => 'Completed',
                'currency_sign' => '$',
                'currency_value' => 1
            ]);
        }
    }
}
