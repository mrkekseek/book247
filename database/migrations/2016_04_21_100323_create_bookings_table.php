<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('by_user_id')->unsigned();
            $table->integer('for_user_id')->unsigned();
            $table->integer('location_id')->unsigned();
            $table->integer('resource_id')->unsigned();
            $table->enum('status',['pending','active','paid','canceled']);
            $table->date('date_of_booking');
            $table->time('booking_time_start');
            $table->time('booking_time_stop');
            $table->enum('payment_type',['cash','membership','recurring']);
            $table->integer('membership_id');
            $table->integer('invoice_id');
            $table->timestamps();

            $table->foreign('by_user_id')->references('id')->on('users');
            $table->foreign('for_user_id')->references('id')->on('users');
            $table->foreign('location_id')->references('id')->on('shop_locations');
            $table->foreign('resource_id')->references('id')->on('shop_resources');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bookings');
    }
}
