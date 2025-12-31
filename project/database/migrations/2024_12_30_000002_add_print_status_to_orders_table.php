<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrintStatusToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('print_status')->default('pending_print')->after('status');
            $table->text('design_data')->nullable()->after('print_status'); // Stores canvas JSON
            $table->string('design_image')->nullable()->after('design_data'); // Exported design image
            $table->timestamp('printed_at')->nullable()->after('design_image');
            $table->timestamp('shipped_at')->nullable()->after('printed_at');
            $table->unsignedBigInteger('printer_id')->nullable()->after('shipped_at'); // Staff who printed
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['print_status', 'design_data', 'design_image', 'printed_at', 'shipped_at', 'printer_id']);
        });
    }
}
