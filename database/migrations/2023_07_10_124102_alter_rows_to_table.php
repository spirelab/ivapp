<?php

use
    Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRowsToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configures', function (Blueprint $table) {
            $table->string('app_color')->nullable()->default('#897ef2');
            $table->string('app_version')->nullable()->default('1.1.0');
            $table->string('app_build')->nullable()->default('25,26,27');
            $table->tinyInteger('is_major')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('configures', function (Blueprint $table) {
            $table->dropColumn('app_color');
            $table->dropColumn('app_version');
            $table->dropColumn('app_build');
            $table->dropColumn('is_major');
        });

    }
}
