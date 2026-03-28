<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlignLegacySchemaForPodManufacturing extends Migration
{
    /**
     * Bring legacy databases in sync with POD/manufacturing schema.
     *
     * This migration is intentionally idempotent:
     * - adds only missing tables/columns
     * - avoids destructive changes
     * - avoids strict foreign keys due legacy id-type differences
     *
     * @return void
     */
    public function up()
    {
        $this->ensureOrdersColumns();
        $this->ensureProductsColumns();
        $this->ensureGeneralSettingsColumns();
        $this->ensureMockupTemplatesTable();
        $this->ensurePodPricingOptionsTable();
        $this->ensurePrintJobsTable();
    }

    protected function ensureOrdersColumns()
    {
        if (!Schema::hasTable('orders')) {
            return;
        }

        if (!Schema::hasColumn('orders', 'print_status')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('print_status')->default('manufacturing');
            });
        }

        if (!Schema::hasColumn('orders', 'design_data')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->longText('design_data')->nullable();
            });
        }

        if (!Schema::hasColumn('orders', 'design_image')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('design_image')->nullable();
            });
        }

        if (!Schema::hasColumn('orders', 'printed_at')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->timestamp('printed_at')->nullable();
            });
        }

        if (!Schema::hasColumn('orders', 'shipped_at')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->timestamp('shipped_at')->nullable();
            });
        }

        if (!Schema::hasColumn('orders', 'printer_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->unsignedInteger('printer_id')->nullable();
            });
        }

        if (Schema::hasColumn('orders', 'print_status')) {
            DB::table('orders')
                ->whereNull('print_status')
                ->update(['print_status' => 'manufacturing']);
        }
    }

    protected function ensureProductsColumns()
    {
        if (!Schema::hasTable('products')) {
            return;
        }

        if (!Schema::hasColumn('products', 'production_cap')) {
            Schema::table('products', function (Blueprint $table) {
                $table->integer('production_cap')->default(1);
            });
        }

        if (!Schema::hasColumn('products', 'is_pod')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('is_pod')->default(0);
            });
        }

        if (!Schema::hasColumn('products', 'print_file')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('print_file')->nullable();
            });
        }

        if (!Schema::hasColumn('products', 'design_data')) {
            Schema::table('products', function (Blueprint $table) {
                $table->longText('design_data')->nullable();
            });
        }

        if (!Schema::hasColumn('products', 'print_time_minutes')) {
            Schema::table('products', function (Blueprint $table) {
                $table->integer('print_time_minutes')->default(30);
            });
        }

        if (!Schema::hasColumn('products', 'quality_tier')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('quality_tier')->default('standard');
            });
        }

        if (!Schema::hasColumn('products', 'mockup_template_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedInteger('mockup_template_id')->nullable();
            });
        }

        if (!Schema::hasColumn('products', 'print_area_data')) {
            Schema::table('products', function (Blueprint $table) {
                $table->longText('print_area_data')->nullable();
            });
        }

        if (!Schema::hasColumn('products', 'requires_approval')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('requires_approval')->default(0);
            });
        }

        if (!Schema::hasColumn('products', 'auto_generate_mockup')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('auto_generate_mockup')->default(1);
            });
        }
    }

    protected function ensureGeneralSettingsColumns()
    {
        if (!Schema::hasTable('generalsettings')) {
            return;
        }

        if (!Schema::hasColumn('generalsettings', 'pod_designer_mode')) {
            Schema::table('generalsettings', function (Blueprint $table) {
                $table->tinyInteger('pod_designer_mode')->default(1);
            });
        }
    }

    protected function ensureMockupTemplatesTable()
    {
        if (Schema::hasTable('mockup_templates')) {
            return;
        }

        Schema::create('mockup_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('product_type', 50);
            $table->string('style', 100)->default('regular');
            $table->string('color', 50)->default('white');
            $table->string('image')->nullable();
            $table->integer('design_x')->default(150);
            $table->integer('design_y')->default(150);
            $table->integer('design_width')->default(200);
            $table->integer('design_height')->default(200);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    protected function ensurePodPricingOptionsTable()
    {
        if (Schema::hasTable('pod_pricing_options')) {
            return;
        }

        Schema::create('pod_pricing_options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category');
            $table->string('name');
            $table->string('value');
            $table->decimal('price', 8, 2);
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    protected function ensurePrintJobsTable()
    {
        if (Schema::hasTable('print_jobs')) {
            return;
        }

        Schema::create('print_jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->unsignedInteger('product_id');
            $table->string('design_file');
            $table->string('mockup_preview')->nullable();
            $table->integer('quantity')->default(1);
            $table->string('quality_tier')->default('standard');
            $table->enum('status', ['queued', 'printing', 'completed', 'failed', 'on_hold'])->default('queued');
            $table->unsignedInteger('printer_id')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('estimated_time_minutes')->nullable();
            $table->integer('actual_time_minutes')->nullable();
            $table->text('notes')->nullable();
            $table->integer('priority')->default(3);
            $table->timestamps();

            $table->index(['status', 'priority', 'created_at']);
            $table->index('printer_id');
            $table->index('order_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse only what this migration added when possible.
     * Kept conservative to avoid accidental data loss in legacy databases.
     *
     * @return void
     */
    public function down()
    {
        // Intentionally left non-destructive for legacy alignment.
    }
}

