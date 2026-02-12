<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Role;
use App\Models\Admin;

echo "=== ROLES IN DATABASE ===\n\n";
$roles = Role::all();

foreach ($roles as $role) {
    echo "ID: {$role->id}\n";
    echo "Name: {$role->name}\n";
    echo "Section: {$role->section}\n";
    echo "Admins with this role: " . $role->admins()->count() . "\n";
    echo "---\n";
}

echo "\n=== ADMIN USERS ===\n\n";
$admins = Admin::with('role')->get();

foreach ($admins as $admin) {
    echo "ID: {$admin->id} | Name: {$admin->name} | Email: {$admin->email}\n";
    echo "  Role ID: {$admin->role_id} | Role: {$admin->role->name}\n";
    if ($admin->role_id > 0) {
        echo "  Sections: {$admin->role->section}\n";
    }
    echo "---\n";
}
