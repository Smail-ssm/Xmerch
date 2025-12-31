<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('print_jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->string('design_file');
            $table->string('mockup_preview')->nullable();
            $table->integer('quantity')->default(1);
            $table->string('quality_tier')->default('standard'); // standard, premium, deluxe
            $table->enum('status', ['queued', 'printing', 'completed', 'failed', 'on_hold'])->default('queued');
            $table->unsignedBigInteger('printer_id')->nullable(); // assigned printer (admin user)
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('estimated_time_minutes')->nullable();
            $table->integer('actual_time_minutes')->nullable();
            $table->text('notes')->nullable();
            $table->integer('priority')->default(3); // 1=high, 2=medium, 3=low
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('printer_id')->references('id')->on('admins')->onDelete('set null');
            
            $table->index(['status', 'priority', 'created_at']);
            $table->index('printer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('print_jobs');
    }
}
