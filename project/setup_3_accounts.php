<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Admin;
use App\Models\Role;

echo "=== SETTING UP 3 DISTINCT ACCOUNTS ===\n\n";

// Get admin password
$admin = Admin::find(1);
$passwordHash = $admin->password;

echo "Password: Same as admin@gmail.com\n\n";

// Check current roles
echo "CURRENT ROLES:\n";
$roles = Role::whereIn('id', [20, 21, 22])->get();
foreach ($roles as $role) {
    echo "  ID {$role->id}: {$role->name} - Sections: {$role->section}\n";
}

echo "\n" . str_repeat("=", 70) . "\n\n";

// Define the 3 accounts we need
$accounts = [
    [
        'email' => 'printer@test.com',
        'name' => 'Printer Only',
        'role_id' => 22, // printer role
        'expected_section' => 'print_production',
        'description' => 'Printer only - can print products'
    ],
    [
        'email' => 'manufacturing@test.com',
        'name' => 'Manufacturing Only',
        'role_id' => 20, // Manifacturing role
        'expected_section' => 'manufacturing',
        'description' => 'Manufacturing only - handles production'
    ],
    [
        'email' => 'allinone@test.com',
        'name' => 'All-in-One Staff',
        'role_id' => 21, // printer all in one role
        'expected_section' => 'print_production , manufacturing',
        'description' => 'Full access - printer + manufacturing'
    ]
];

// Create or update accounts
foreach ($accounts as $account) {
    $role = Role::find($account['role_id']);
   
    echo "📧 {$account['email']}\n";
    echo "   Name: {$account['name']}\n";
    echo "   Role: {$role->name} (ID: {$role->id})\n";
    echo "   Sections: {$role->section}\n";
    echo "   Description: {$account['description']}\n";
    
    $existing = Admin::where('email', $account['email'])->first();
    
    if ($existing) {
        $existing->name = $account['name'];
        $existing->password = $passwordHash;
        $existing->role_id = $account['role_id'];
        $existing->save();
        echo "   Status: ✅ Updated\n";
    } else {
        Admin::create([
            'name' => $account['name'],
            'email' => $account['email'],
            'password' => $passwordHash,
            'role_id' => $account['role_id'],
            'phone' => '',
        ]);
        echo "   Status: ✅ Created\n";
    }
    echo "\n";
}

echo str_repeat("=", 70) . "\n\n";

echo "🔐 LOGIN CREDENTIALS:\n\n";
echo "1️⃣  PRINTER ONLY\n";
echo "    Email: printer@test.com\n";
echo "    Access: Print production tasks only\n";
echo "    Can do: Start printing, mark printed, ship orders\n\n";

echo "2️⃣  MANUFACTURING ONLY\n";
echo "    Email: manufacturing@test.com\n";
echo "    Access: Manufacturing tasks only\n";
echo "    Can do: Same printer views but from manufacturing perspective\n\n";

echo "3️⃣  ALL-IN-ONE\n";
echo "    Email: allinone@test.com\n";
echo "    Access: Both printer AND manufacturing\n";
echo "    Can do: Everything!\n\n";

echo "🔑 Password for all: Same as admin@gmail.com\n\n";

echo "✅ All 3 accounts now work with updated permissions!\n";
echo "   Routes accept: print_production OR manufacturing\n";
