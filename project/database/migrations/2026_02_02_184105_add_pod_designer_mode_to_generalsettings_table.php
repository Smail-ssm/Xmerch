<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPodDesignerModeToGeneralsettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->tinyInteger('pod_designer_mode')->default(1)->after('affilite');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->dropColumn('pod_designer_mode');
        });
    }
}
