<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('booking_id')->unsigned();
            $table->integer('by_user_id')->unsigned();
            $table->string('note_title', 150);
            $table->text('note_body');
            $table->string('note_type', 100);
            $table->enum('privacy',['admin','employees','everyone']);
            $table->enum('status',['unread','read','deleted']);

            $table->timestamps();
            $table->foreign('booking_id')->references('id')->on('bookings');
            $table->foreign('by_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('booking_notes');
    }
}
