<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Role;
use App\Models\Admin;

$output = "=== ROLES IN DATABASE ===\n\n";
$roles = Role::all();

foreach ($roles as $role) {
    $output .= "ID: {$role->id}\n";
    $output .= "Name: {$role->name}\n";
    $output .= "Section: {$role->section}\n";
    $output .= "Admins with this role: " . $role->admins()->count() . "\n";
    $output .= str_repeat("-", 80) . "\n\n";
}

$output .= "\n=== ADMIN USERS ===\n\n";
$admins = Admin::with('role')->get();

foreach ($admins as $admin) {
    $output .= "ID: {$admin->id} | Name: {$admin->name} | Email: {$admin->email}\n";
    $output .= "  Role ID: {$admin->role_id} | Role: {$admin->role->name}\n";
    if ($admin->role_id > 0) {
        $output .= "  Sections: {$admin->role->section}\n";
    }
    $output .= str_repeat("-", 80) . "\n\n";
}

// Check if print_production section exists
$output .= "\n=== PRINT PRODUCTION ANALYSIS ===\n\n";
$printRoles = Role::where('section', 'like', '%print_production%')->get();
$output .= "Roles with 'print_production' section: " . $printRoles->count() . "\n\n";

foreach ($printRoles as $role) {
    $output .=  "  - {$role->name} (ID: {$role->id})\n";
}

// Check manufacturing section
$mfgRoles = Role::where('section', 'like', '%manufacturing%')->get();
$output .= "\nRoles with 'manufacturing' section: " . $mfgRoles->count() . "\n\n";

foreach ($mfgRoles as $role) {
    $output .= "  - {$role->name} (ID: {$role->id})\n";
}

file_put_contents(__DIR__ . '/ROLES_ANALYSIS.txt', $output);
echo "Report saved to ROLES_ANALYSIS.txt\n";
echo $output;
