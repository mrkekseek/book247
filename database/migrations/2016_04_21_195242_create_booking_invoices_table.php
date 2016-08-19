<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_invoices', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('booking_id')->unsigned();
            $table->string('invoice_number', 64);
            $table->text('other_details');
            $table->enum('status', ['pending','ordered','processing','completed','cancelled','declined','incomplete','preordered']);
            $table->timestamps();

            $table->foreign('booking_id')->references('id')->on('bookings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('booking_invoices');
    }
}
