<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPodFieldsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('print_time_minutes')->default(30)->after('production_cap');
            $table->string('quality_tier')->default('standard')->after('print_time_minutes');
            $table->unsignedBigInteger('mockup_template_id')->nullable()->after('quality_tier');
            $table->json('print_area_data')->nullable()->after('mockup_template_id');
            $table->boolean('requires_approval')->default(0)->after('print_area_data');
            $table->boolean('auto_generate_mockup')->default(1)->after('requires_approval');
            
            $table->foreign('mockup_template_id')->references('id')->on('mockup_templates')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['mockup_template_id']);
            $table->dropColumn([
                'print_time_minutes',
                'quality_tier',
                'mockup_template_id',
                'print_area_data',
                'requires_approval',
                'auto_generate_mockup'
            ]);
        });
    }
}
