<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThemeConfigToCategoriesAndUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $blueprint) {
            $blueprint->text('theme_config')->nullable();
        });

        Schema::table('users', function (Blueprint $blueprint) {
            $blueprint->text('theme_config')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $blueprint) {
            $blueprint->dropColumn('theme_config');
        });

        Schema::table('users', function (Blueprint $blueprint) {
            $blueprint->dropColumn('theme_config');
        });
    }
}
