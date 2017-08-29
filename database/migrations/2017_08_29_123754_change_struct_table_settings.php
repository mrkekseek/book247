<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStructTableSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
           $table->engine = 'InnoDB';
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->integer('setting_group')->nullable()->default(0)->after('id');
            $table->enum("visibility", ["all", "club", "federation"])->default("all")->after('setting_group');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function($table)
        {
            $table->dropColumn('setting_group');
            $table->dropColumn('visibility');
        });
    }
}
