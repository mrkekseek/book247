<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeShopOpeningHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::table('shop_opening_hours', function ($table) {
            $table->dropForeign(['location_id']);
        });*/

        Schema::drop('shop_opening_hours');

        Schema::create('shop_opening_hours', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_location_id')->unsigned();
            $table->string('days', 50);
            $table->time('time_start');
            $table->time('time_stop');
            $table->date('date_start')->nullable();
            $table->date('date_stop')->nullable();
            $table->enum('type',['open_hours','break_hours','close_hours'])->default('open_hours');
            $table->timestamps();
        });

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::table('shop_opening_hours', function($table) {
            $table->foreign('shop_location_id')
                ->references('id')
                ->on('shop_locations');
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
        Schema::drop('shop_opening_hours');
    }
}
