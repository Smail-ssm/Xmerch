<?php

namespace Tests\Feature\Readiness;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class RolePermissionModelTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_section_check_works_for_print_and_manufacturing_roles()
    {
        $role = Role::create([
            'name' => 'QA Role ' . Str::random(5),
            'section' => 'print_production , manufacturing',
        ]);

        $admin = Admin::create([
            'name' => 'QA Admin ' . Str::random(5),
            'email' => 'admin_' . Str::random(8) . '@example.com',
            'password' => bcrypt('secret123'),
            'role_id' => $role->id,
            'phone' => '',
        ]);

        $this->assertTrue($admin->sectionCheck('print_production'));
        $this->assertTrue($admin->sectionCheck('manufacturing'));
        $this->assertFalse($admin->sectionCheck('orders'));
    }
}

