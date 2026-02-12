<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdatePrintStatusDefaultToManufacturing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Change default value to 'manufacturing'
        DB::statement("ALTER TABLE orders ALTER COLUMN print_status SET DEFAULT 'manufacturing'");
        
        // Update existing 'pending_print' orders to 'manufacturing' if they haven't been touched
        DB::table('orders')
            ->where('print_status', 'pending_print')
            ->where('status', 'processing')
            ->update(['print_status' => 'manufacturing']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE orders ALTER COLUMN print_status SET DEFAULT 'pending_print'");
    }
}
