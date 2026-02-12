<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

// Create test customer
$user = new User();
$user->name = 'Test Customer';
$user->email = 'testcustomer@xmerch.com';
$user->password = bcrypt('admin123');
$user->email_verified = 'Yes';
$user->affilate_code = md5('testcustomer@xmerch.com');
$user->save();

echo "✅ Test Customer Created!\n";
echo "ID: " . $user->id . "\n";
echo "Email: testcustomer@xmerch.com\n";
echo "Password: admin123\n";
echo "\nLogin at: /user/login\n";
