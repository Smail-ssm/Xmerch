<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Admin;
use App\Models\Role;

// Get admin password hash
$admin = Admin::find(1);
$passwordHash = $admin->password;

echo "=== CREATING TEST ACCOUNTS ===\n\n";
echo "Using same password as Admin (ID: 1)\n";
echo "Password hash: {$passwordHash}\n\n";

// Create test accounts for each printer/manufacturing role

$testAccounts = [
    [
        'name' => 'Test Printer',
        'email' => 'printer@test.com',
        'role_id' => 22, // printer role
        'role_name' => 'printer'
    ],
    [
        'name' => 'Test All-in-One',
        'email' => 'allinone@test.com',
        'role_id' => 21, // printer all in one role
        'role_name' => 'printer all in one'
    ],
    [
        'name' => 'Test Manufacturing',
        'email' => 'manufacturing@test.com',
        'role_id' => 20, // Manufacturing role
        'role_name' => 'Manifacturing'
    ],
];

foreach ($testAccounts as $account) {
    // Check if account already exists
    $existing = Admin::where('email', $account['email'])->first();
    
    if ($existing) {
        echo "⚠️  Account already exists: {$account['email']}\n";
        echo "   Updating password and role...\n";
        $existing->password = $passwordHash;
        $existing->role_id = $account['role_id'];
        $existing->name = $account['name'];
        $existing->save();
        echo "   ✅ Updated!\n\n";
    } else {
        $newAdmin = Admin::create([
            'name' => $account['name'],
            'email' => $account['email'],
            'password' => $passwordHash,
            'role_id' => $account['role_id'],
            'phone' => '',
            'photo' => null,
            'shop_name' => null,
        ]);
        echo "✅ Created: {$account['name']}\n";
        echo "   Email: {$account['email']}\n";
        echo "   Role: {$account['role_name']} (ID: {$account['role_id']})\n";
        echo "   Password: Same as admin\n\n";
    }
}

echo "\n=== ACCOUNT SUMMARY ===\n\n";
$allAdmins = Admin::with('role')->whereIn('role_id', [20, 21, 22])->get();

foreach ($allAdmins as $admin) {
    echo "Email: {$admin->email}\n";
    echo "Name: {$admin->name}\n";
    echo "Role: {$admin->role->name} (ID: {$admin->role_id})\n";
    echo "Sections: {$admin->role->section}\n";
    echo str_repeat("-", 60) . "\n";
}

echo "\n💡 LOGIN CREDENTIALS:\n";
echo "Email: printer@test.com | Role: printer (print_production only)\n";
echo "Email: allinone@test.com | Role: printer all in one (print_production + manufacturing)\n";
echo "Email: manufacturing@test.com | Role: Manifacturing (manufacturing only)\n";
echo "\nPassword for all: Same as admin@gmail.com\n";
