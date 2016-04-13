<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopOpeningHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_opening_hours', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('location_id')->unsigned();
            $table->enum('day_of_week', [1,2,3,4,5,6,7]);
            $table->date('specific_day');
            $table->enum('entry_type', ['day','specific']);
            $table->string('open_at',5);
            $table->string('close_at',5);
            $table->string('break_from',5);
            $table->string('break_to',5);
            $table->timestamps();

            $table->foreign('location_id')->references('id')->on('shop_locations');
        });
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
