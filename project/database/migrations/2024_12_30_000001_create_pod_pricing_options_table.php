<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePodPricingOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pod_pricing_options', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // 'print_quality', 'clothing_quality', 'production_speed', etc.
            $table->string('name'); // Display name
            $table->string('value'); // Option value/slug
            $table->decimal('price', 8, 2); // Operational price
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pod_pricing_options');
    }
}
