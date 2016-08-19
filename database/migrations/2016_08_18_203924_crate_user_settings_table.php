<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrateUserSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('user_id')->length(10)->unsigned()->nullable();
            $table->string('var_name');
            $table->string('var_value');
            $table->timestamps();
        });

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::table('user_settings', function($table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_settings');
    }
}
