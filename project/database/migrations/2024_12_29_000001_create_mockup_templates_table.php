<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMockupTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mockup_templates', function (Blueprint $table) {
            $table->id();
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mockup_templates');
    }
}
