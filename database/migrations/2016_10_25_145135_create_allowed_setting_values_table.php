<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllowedSettingValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allowed_setting_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('setting_id')->unsigned()->nullable();
            $table->string('item_value',25);
            $table->string('caption',50);
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
        Schema::drop('allowed_setting_values');
    }
}
