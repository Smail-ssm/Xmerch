<?php

namespace Tests\Feature\Readiness;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SchemaReadinessTest extends TestCase
{
    public function test_critical_tables_and_columns_exist_for_core_flows()
    {
        $required = [
            'users' => ['id', 'name', 'email', 'password', 'is_vendor', 'email_verified', 'ban'],
            'admins' => ['id', 'name', 'email', 'password', 'role_id'],
            'roles' => ['id', 'name', 'section'],
            'verifications' => ['id', 'user_id', 'status', 'admin_warning', 'warning_reason'],
            'generalsettings' => ['id', 'is_verification_email', 'verify_product', 'pod_designer_mode'],
            'products' => ['id', 'user_id', 'category_id', 'name', 'photo', 'price', 'type', 'is_pod', 'production_cap', 'print_file'],
            'orders' => ['id', 'cart', 'status', 'payment_status', 'order_number', 'print_status', 'printed_at', 'shipped_at'],
            'payment_gateways' => ['id', 'keyword', 'name', 'information', 'currency_id', 'checkout', 'deposit', 'subscription'],
            'print_jobs' => ['id', 'order_id', 'product_id', 'design_file', 'status', 'printer_id'],
            'mockup_templates' => ['id', 'name', 'product_type', 'design_x', 'design_y', 'design_width', 'design_height'],
            'pod_pricing_options' => ['id', 'category', 'name', 'value', 'price', 'is_active'],
        ];

        foreach ($required as $table => $columns) {
            $this->assertTrue(Schema::hasTable($table), "Missing table: {$table}");
            foreach ($columns as $column) {
                $this->assertTrue(
                    Schema::hasColumn($table, $column),
                    "Missing column: {$table}.{$column}"
                );
            }
        }
    }
}

