<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('setting_id')->unsigned()->nullable();
            $table->integer('allowed_setting_value_id')->nullable();
            $table->string('unconstrained_value', 25)->nullable();
            $table->integer('updated_by_id')->unsigned();

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
        Schema::drop('application_settings');
    }
}
